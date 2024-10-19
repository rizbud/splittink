<script setup>
import { ref, reactive, defineProps, watch, computed } from "vue";
import { router } from "@inertiajs/vue3";
import axios from "axios";
import { Input, NegativeButton, PrimaryButton } from ".";
import { formatThousands, toast } from "../utils";

const { bill, currencies, group, ...props } = defineProps({
    group: Object,
    bill: Object,
    billParticipants: Array,
    currencies: Array,
});

const isEditing = !!bill;

const billParticipants = props.billParticipants?.map((participant) => ({
    participantId: participant.participant_id,
    name: participant.participant.name,
    hasPaid: participant.paid_amount > 0,
    paidAmount: participant.paid_amount,
}));

const participants = group.participants.map((participant) => {
    const billParticipant = billParticipants?.find(
        (billParticipant) => billParticipant.participantId === participant.id
    );
    return {
        participantId: participant.id,
        name: participant.name,
        hasPaid: isEditing ? billParticipant?.hasPaid : false,
        paidAmount: isEditing ? billParticipant?.paidAmount : 0,
    };
});

const form = reactive({
    name: bill?.name || "",
    currency_id: bill?.currency_id || group?.currency_id || 1,
    amount: bill?.amount || 0,
    participants: billParticipants || participants || [],
    // splitting_method: bill?.splitting_method || "equally",
});

const baseCurrency = currencies.find(
    (currency) => currency.id === group.currency_id
);
const baseRate = baseCurrency.exchange_rate;

const selectedCurrency = computed(() => {
    return currencies.find((currency) => currency.id === form.currency_id);
});
const selectedRate = computed(() => {
    return selectedCurrency.value.exchange_rate;
});

const amountInBaseCurrency = computed(() => {
    const rate = (1 / selectedRate.value) * baseRate;
    return formatThousands(form.amount * rate, baseCurrency.decimal_digits);
});

const totalPaidAmount = computed(() => {
    const amount = form.participants.reduce(
        (acc, participant) =>
            acc + (participant.hasPaid ? participant.paidAmount : 0),
        0
    );
    const remainingAmount = form.amount - amount;
    const isMatched = form.amount === amount;
    const isOverpaid = amount > form.amount;

    return {
        amount: formatThousands(amount, selectedCurrency.value.decimal_digits),
        remainingAmount: formatThousands(
            Math.abs(remainingAmount),
            selectedCurrency.value.decimal_digits
        ),
        isMatched,
        isOverpaid,
    };
});

const isLoading = ref(false);
const isDeleting = ref(false);
const isDisabled = computed(() => {
    return (
        !form.name ||
        !form.participants.length ||
        form.amount <= 0 ||
        !totalPaidAmount.value.isMatched ||
        isLoading.value
    );
});

const findParticipant = (participantId) => {
    return form.participants.find(
        (participant) => participant.participantId === participantId
    );
};

const hasPaid = (participantId) => {
    const participant = findParticipant(participantId);
    return participant?.hasPaid;
};

const handleHasPaid = (participantId) => {
    const participant = findParticipant(participantId);
    participant.hasPaid = !participant.hasPaid;
};

const submitForm = async () => {
    const formParticipants = form.participants.map((participant) => ({
        participant_id: participant.participantId,
        paid_amount: participant.hasPaid ? participant.paidAmount : 0,
    }));

    const payload = {
        ...form,
        participants: formParticipants,
    };

    try {
        isLoading.value = true;
        const response = isEditing
            ? await axios.put(`/groups/${group.id}/bills/${bill.id}`, payload)
            : await axios.post(`/groups/${group.id}/add-bill`, payload);

        if (response.status === 200 || response.status === 201) {
            toast.success(
                `Bill ${isEditing ? "updated" : "added"} successfully.`
            );
            router.get(`/groups/${group.slug}`);
        } else {
            throw new Error();
        }
    } catch (error) {
        const errorMessage =
            error.response?.data?.message ||
            error.message ||
            `Failed to ${isEditing ? "update" : "add"} bill.`;
        toast.error(errorMessage);
    } finally {
        isLoading.value = false;
    }
};

const handleDelete = async () => {
    const confirm = window.confirm(
        "Are you sure you want to delete this bill?"
    );
    if (!confirm) return;
    try {
        isDeleting.value = true;
        const response = await axios.delete(
            `/groups/${group.id}/bills/${bill.id}`
        );

        if (response.status === 204) {
            toast.success("Bill deleted successfully.");
            router.get(`/groups/${group.slug}`);
        } else {
            throw new Error();
        }
    } catch (error) {
        const errorMessage =
            error.response?.data?.message ||
            error.message ||
            "Failed to delete bill.";
        toast.error(errorMessage);
    } finally {
        isDeleting.value = false;
    }
};
</script>

<template>
    <form
        @submit.prevent="submitForm"
        class="flex flex-col gap-4 text-slate-600"
    >
        <Input
            id="bill-name"
            v-model="form.name"
            label="Bill or Payment Name"
            placeholder="Taxi fare from airport to hotel"
            required
            maxlength="100"
        />

        <div class="space-y-1">
            <label for="amount" class="inline-block text-sm">
                Total Amount<span class="text-red-500">*</span>
            </label>
            <div
                class="flex items-center border border-gray-300 rounded overflow-hidden"
            >
                <select
                    id="currency_id"
                    name="currency_id"
                    v-model="form.currency_id"
                    class="p-2 focus:outline-none focus:ring-0 border-r w-fit"
                >
                    <option
                        v-for="currency in currencies"
                        :key="currency.id"
                        :value="currency.id"
                        :selected="currency.id === form.currency_id"
                    >
                        {{ currency.code }} ({{ currency.symbol }})
                    </option>
                </select>

                <input
                    id="amount"
                    type="number"
                    name="amount"
                    v-model="form.amount"
                    class="w-full p-2 focus:outline-none focus:ring-0"
                    placeholder="10.00"
                    max="2147483647"
                    required
                />
            </div>
            <span class="text-sm" v-if="form.amount">
                Amount in {{ baseCurrency.code }}:
                <span class="text-base">
                    {{ amountInBaseCurrency }}
                </span>
            </span>
        </div>

        <div class="flex flex-col gap-1">
            <label class="text-sm">Who is participating?</label>
            <ul class="max-h-80 overflow-y-auto space-y-3 border rounded p-2">
                <li
                    v-for="participant in participants"
                    :key="participant.participantId"
                    class="flex flex-col px-2 border rounded"
                >
                    <div class="flex items-center gap-2">
                        <input
                            type="checkbox"
                            :id="`participant-${participant.participantId}`"
                            :value="participant"
                            v-model="form.participants"
                            class="focus:ring-0"
                        />
                        <label
                            :for="`participant-${participant.participantId}`"
                            class="py-2 w-full"
                        >
                            {{ participant.name }}
                        </label>

                        <div class="flex items-center gap-2">
                            <label
                                :for="`participant-${participant.participantId}-has-paid`"
                                class="py-2 text-nowrap"
                                :class="{
                                    'text-slate-400': !findParticipant(
                                        participant.participantId
                                    ),
                                }"
                            >
                                Has Paid?
                            </label>
                            <input
                                type="checkbox"
                                :id="`participant-${participant.participantId}-has-paid`"
                                :value="true"
                                :checked="hasPaid(participant.participantId)"
                                @change="
                                    () =>
                                        handleHasPaid(participant.participantId)
                                "
                                :disabled="
                                    !findParticipant(participant.participantId)
                                "
                                class="focus:ring-0"
                            />
                        </div>
                    </div>

                    <Input
                        :id="`participant-${participant.participantId}-paid-amount`"
                        :label="`Amount Paid by ${participant.name} (in ${selectedCurrency.code})`"
                        type="number"
                        placeholder="10.00"
                        :value="
                            findParticipant(participant.participantId)
                                .paidAmount
                        "
                        @input="
                            findParticipant(
                                participant.participantId
                            ).paidAmount = parseFloat($event.target.value || 0)
                        "
                        required
                        v-if="hasPaid(participant.participantId)"
                        class="pb-2"
                    />
                </li>
            </ul>

            <span class="text-sm self-end">
                Total Paid Amount in {{ selectedCurrency.code }}:
                <span
                    class="text-base"
                    :class="{
                        'text-emerald-500': totalPaidAmount.isMatched,
                        'text-red-500': !totalPaidAmount.isMatched,
                    }"
                >
                    {{ totalPaidAmount.amount }}

                    <span v-if="!totalPaidAmount.isMatched">
                        ({{ totalPaidAmount.isOverpaid ? "+" : "-"
                        }}{{ totalPaidAmount.remainingAmount }})
                    </span>
                </span>
            </span>
        </div>

        <PrimaryButton
            type="submit"
            :text="isEditing ? 'Save Changes' : 'Add Bill'"
            :disabled="isDisabled || isDeleting"
            :is-loading="isLoading"
        />

        <NegativeButton
            v-if="isEditing"
            type="button"
            text="Delete"
            :disabled="isDeleting || isLoading"
            :is-loading="isDeleting"
            @click="handleDelete"
        />
    </form>
</template>

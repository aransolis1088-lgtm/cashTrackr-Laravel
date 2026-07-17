import { create } from 'zustand';

type ExpenseModalStore = {
    isOpen: boolean;
    openCreateModal: () => void;
    closeCreateModal: () => void;
};

export const useExpenseModalStore = create<ExpenseModalStore>((set) => ({
    isOpen: false,
    openCreateModal: () => set({ isOpen: true }),
    closeCreateModal: () => set({ isOpen: false }),
}));
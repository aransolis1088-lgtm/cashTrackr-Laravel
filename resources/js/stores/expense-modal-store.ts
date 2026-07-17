import { create } from 'zustand';
import { Budget } from '../types/budgets';
import { Category } from '../types/category';

type ExpenseModalStore = {
    isOpen: boolean;
    budget: Budget | null;
    categories: Category[]
    openCreateModal: () => void;
    closeCreateModal: () => void;
    setBudget: (budget: Budget) => void;
    setCategories: (categories: Category[]) => void;
};

export const useExpenseModalStore = create<ExpenseModalStore>((set) => ({
    isOpen: false,
    budget: null,
    categories: [],
    openCreateModal: () => set({ isOpen: true }),
    closeCreateModal: () => set({ isOpen: false }),
    setBudget: (budget: Budget) => set({ budget }),
    setCategories: (categories: Category[]) => set({ categories }),
}));
import { create } from 'zustand';
import { Budget } from '../types/budgets';
import { Category } from '../types/category';
import { Expense } from '../types/expense';
import { devtools, DevtoolsOptions } from 'zustand/middleware';

type ExpenseModalStore = {
    isOpen: boolean;
    budget: Budget | null;
    expense: Expense | null;
    categories: Category[]
    openCreateModal: () => void;
    openEditModal: (expense: Expense) => void;
    closeCreateModal: () => void;
    setBudget: (budget: Budget) => void;
    setCategories: (categories: Category[]) => void;
};

export const useExpenseModalStore = create<ExpenseModalStore>()(devtools((set) => ({
    isOpen: false,
    budget: null,
    expense: null,
    categories: [],
    openCreateModal: () => set({ isOpen: true }),
    openEditModal: (expense) => set({ isOpen: true, expense }),
    closeCreateModal: () => set({ isOpen: false, expense: null }),
    setBudget: (budget: Budget) => set({ budget }),
    setCategories: (categories: Category[]) => set({ categories }),
})));
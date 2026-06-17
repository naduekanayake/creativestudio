<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class ExpenseController extends Controller
{
    public function index()
    {
        $expenses = Expense::latest()->paginate(15);

        $stats = [
            'total'         => Expense::where('status', 'Approved')->sum('amount'),
            'this_month'    => Expense::where('status', 'Approved')
                ->whereMonth('expense_date', now()->month)
                ->whereYear('expense_date', now()->year)
                ->sum('amount'),
            'count'         => Expense::count(),
            'pending'       => Expense::where('status', 'Pending')->count(),
        ];

        $categoryTotals = Expense::where('status', 'Approved')
            ->selectRaw('category, SUM(amount) as total')
            ->groupBy('category')
            ->orderByDesc('total')
            ->get();

        return view('expenses.index', compact('expenses', 'stats', 'categoryTotals'));
    }

    public function create()
    {
        return view('expenses.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'          => 'required|string|max:255',
            'description'    => 'nullable|string',
            'category'       => 'required|in:Equipment,Software,Transport,Food,Marketing,Studio Rent,Utilities,Salary,Other',
            'amount'         => 'required|numeric|min:0.01',
            'expense_date'   => 'required|date',
            'payment_method' => 'required|in:Cash,Bank Transfer,Card,Cheque,Online',
            'receipt_number' => 'nullable|string|max:100',
            'notes'          => 'nullable|string',
            'status'         => 'required|in:Pending,Approved,Rejected',
        ]);

        $expense = Expense::create($validated);

        ActivityLog::log('created', 'Expense', $expense->id, $expense->title,
            'Expense recorded: ' . $expense->title . ' — Rs. ' . number_format($expense->amount),
            'dollar', 'orange');

        return redirect()->route('expenses.index')
            ->with('success', 'Expense recorded successfully!');
    }

    public function show(Expense $expense)
    {
        return view('expenses.show', compact('expense'));
    }

    public function edit(Expense $expense)
    {
        return view('expenses.edit', compact('expense'));
    }

    public function update(Request $request, Expense $expense)
    {
        $validated = $request->validate([
            'title'          => 'required|string|max:255',
            'description'    => 'nullable|string',
            'category'       => 'required|in:Equipment,Software,Transport,Food,Marketing,Studio Rent,Utilities,Salary,Other',
            'amount'         => 'required|numeric|min:0.01',
            'expense_date'   => 'required|date',
            'payment_method' => 'required|in:Cash,Bank Transfer,Card,Cheque,Online',
            'receipt_number' => 'nullable|string|max:100',
            'notes'          => 'nullable|string',
            'status'         => 'required|in:Pending,Approved,Rejected',
        ]);

        $expense->update($validated);

        ActivityLog::log('updated', 'Expense', $expense->id, $expense->title,
            'Expense updated: ' . $expense->title, 'dollar', 'orange');

        return redirect()->route('expenses.index')
            ->with('success', 'Expense updated successfully!');
    }

    public function destroy(Expense $expense)
    {
        $title = $expense->title;
        $expense->delete();

        ActivityLog::log('deleted', 'Expense', null, $title,
            'Expense deleted: ' . $title, 'dollar', 'red');

        return redirect()->route('expenses.index')
            ->with('success', 'Expense deleted successfully!');
    }
}
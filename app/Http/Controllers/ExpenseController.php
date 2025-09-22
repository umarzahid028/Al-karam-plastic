<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Expense;

class ExpenseController extends Controller
{
    public function index()
    {
        $expenses = Expense::orderBy('expense_date', 'desc')->paginate(10);
        $total    = Expense::sum('amount');

        return view('expenses.index', compact('expenses', 'total'));
    }

    public function create()
    {
        // Auto-generate a prefix like EXP-25-
        $year   = date('y');
        $prefix = 'EXP-' . $year . '-';

        return view('expenses.create', compact('prefix'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'expense_no'       => 'required',            // matches form field
            'expense_category' => 'required',
            'expense_type'     => 'required',
            'amount'           => 'required|numeric',
            'expense_date'     => 'required|date',
            'approved_by'      => 'required',
            'vendor'           => 'nullable',
            'payment_method'   => 'nullable',
            'reference_no'     => 'nullable',
            'salesperson'      => 'nullable',
            'description'      => 'nullable',
        ]);

        // If there is an attachment, store it
        if ($request->hasFile('attachment')) {
            $validated['attachment'] = $request->file('attachment')->store('attachments', 'public');
        }

        Expense::create($validated);

        return redirect()
            ->route('expenses.index')
            ->with('success', 'Expense saved successfully!');
    }

    public function show($id)
    {
        $expense = Expense::findOrFail($id);
        return view('expenses.show', compact('expense'));
    }

    public function edit($id)
    {
        $expense = Expense::findOrFail($id);
        return view('expenses.edit', compact('expense'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'expense_no'       => 'required',
            'expense_category' => 'required',
            'expense_type'     => 'required',
            'amount'           => 'required|numeric',
            'expense_date'     => 'required|date',
            'approved_by'      => 'required',
            'vendor'           => 'nullable',
            'payment_method'   => 'nullable',
            'reference_no'     => 'nullable',
            'salesperson'      => 'nullable',
            'description'      => 'nullable',
        ]);

        if ($request->hasFile('attachment')) {
            $validated['attachment'] = $request->file('attachment')->store('attachments', 'public');
        }

        $expense = Expense::findOrFail($id);
        $expense->update($validated);

        return redirect()
            ->route('expenses.index')
            ->with('success', 'Expense updated successfully!');
    }

    public function destroy($id)
    {
        $expense = Expense::findOrFail($id);
        $expense->delete();

        return redirect()
            ->route('expenses.index')
            ->with('success', 'Expense deleted successfully!');
    }
}

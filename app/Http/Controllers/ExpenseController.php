<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Expense;

class ExpenseController extends Controller
{
    public function index()
    {
        $expenses = Expense::orderBy('expense_date', 'asc')->paginate(5);
        $total = Expense::sum('amount');

        return view('expenses.index', compact('expenses', 'total'));
    }

    public function create()
    {
        $year = date('y'); // short year e.g. 25
        $prefix = 'EXP-' . $year . '-';
    
        return view('expenses.create', compact('prefix'));
    }
    
    public function store(Request $request)
{
    $request->validate([
        'expense_number'   => 'required|numeric',
        'expense_category' => 'required',
        'expense_type'     => 'required',
        'amount'           => 'required|numeric',
        'expense_date'     => 'required|date',
        'approved_by'      => 'required',
    ]);

    $year = date('y');
    $prefix = 'EXP-' . $year . '-';

    // Full expense number banayenge prefix + user input se
    $expenseNo = $prefix . str_pad($request->expense_number, 5, '0', STR_PAD_LEFT);

    if (Expense::where('expense_no', $expenseNo)->exists()) {
        return back()->withErrors(['expense_number' => 'Expense No already exists!'])->withInput();
    }

    $expense = new Expense();
    $expense->expense_no       = $expenseNo;
    $expense->expense_category = $request->expense_category;
    $expense->expense_type     = $request->expense_type;
    $expense->vendor           = $request->vendor;
    $expense->payment_method   = $request->payment_method;
    $expense->amount           = $request->amount;
    $expense->expense_date     = $request->expense_date;
    $expense->reference_no     = $request->reference_no;

    if ($request->hasFile('attachment')) {
        $expense->attachment = $request->file('attachment')->store('attachments', 'public');
    }

    $expense->approved_by = $request->approved_by;
    $expense->salesperson = $request->salesperson;
    $expense->description = $request->description;

    $expense->save();

    return redirect()->route('expenses.index')->with('success', 'Expense saved successfully!');
}

    public function storeMultiple(Request $request)
    {
        $expenses = $request->input('expenses');

        if ($expenses && is_array($expenses)) {
            foreach ($expenses as $exp) {
                $data = [
                    'expense_category' => $exp['expense_category'] ?? null,
                    'expense_type'     => $exp['expense_type'] ?? null,
                    'vendor'           => $exp['vendor'] ?? null,
                    'payment_method'   => $exp['payment_method'] ?? null,
                    'amount'           => $exp['amount'] ?? 0,
                    'expense_date'     => $exp['expense_date'] ?? now(),
                    'reference_no'     => $exp['reference_no'] ?? null,
                    'approved_by'      => $exp['approved_by'] ?? null,
                    'salesperson'      => $exp['salesperson'] ?? null,
                    'description'      => $exp['description'] ?? null,
                ];

                if (isset($exp['attachment']) && $exp['attachment'] instanceof \Illuminate\Http\UploadedFile) {
                    $data['attachment'] = $exp['attachment']->store('attachments', 'public');
                }

                Expense::create($data);
            }
        }

        return redirect()->route('expenses.index')->with('success', 'Expenses added successfully!');
    }

    public function show($id)
    {
        $expense = Expense::findOrFail($id);
        return view('expenses.show', compact('expense'));
    }
}

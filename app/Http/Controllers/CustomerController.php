<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $customers = Customer::when($request->has('search'), function ($query) use ($request) {
            $query->where('first_name', 'LIKE', "%$request->search%")
                ->orWhere('last_name', 'LIKE', "%$request->search%")
                ->orWhere('phone', 'LIKE', "%$request->search%")
                ->orWhere('email', 'LIKE', "%$request->search%");
        })->orderBy('id', $request->order === 'asc' ? 'asc' : 'desc')->get();

        return inertia('Customer/Index', [
            'customers' => $customers,
            'query' => $request->query()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return inertia('Customer/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'image' => ['nullable', 'image', 'max:2000', 'mimes:png,jpg,jpeg,webp'],
            'first_name' => ['required', 'max:255', 'string'],
            'last_name' => ['required', 'max:255', 'string'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['required', 'string', 'max:50'],
            'bank_account_number' => ['required', 'numeric'],
            'about' => ['nullable', 'string', 'max:500'],
        ]);

        if ($request->hasFile('image')) {
            $fileName = $request->file('image')->store('uploads', 'public');
            $validatedData['image'] = $fileName;
        }

        Customer::create($validatedData);

        return redirect()->route('customers.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Customer $customer)
    {
        return inertia('Customer/Show', [
            'customer' => $customer
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Customer $customer)
    {
        return inertia('Customer/Edit', [
            'customer' => $customer
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Customer $customer)
    {
        $validatedData = $request->validate([
            'image' => ['nullable', 'image', 'max:2000', 'mimes:png,jpg,jpeg,webp'],
            'first_name' => ['required', 'max:255', 'string'],
            'last_name' => ['required', 'max:255', 'string'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['required', 'string', 'max:50'],
            'bank_account_number' => ['required', 'numeric'],
            'about' => ['nullable', 'string', 'max:500'],
        ]);

        $validatedData['image'] = $customer->image;

        if ($request->hasFile('image')) {
            if ($customer->image) {
                Storage::disk('public')->delete($customer->image);
            }

            $fileName = $request->file('image')->store('uploads', 'public');
            $validatedData['image'] = $fileName;
        }

        $customer->update($validatedData);

        return redirect()->route('customers.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Customer $customer)
    {
        $customer->delete();

        return back();
    }

    public function trashIndex(Request $request)
    {
        $customers = Customer::onlyTrashed()->when($request->has('search'), function ($query) use ($request) {
            $query->where('first_name', 'LIKE', "%$request->search%")
                ->orWhere('last_name', 'LIKE', "%$request->search%")
                ->orWhere('phone', 'LIKE', "%$request->search%")
                ->orWhere('email', 'LIKE', "%$request->search%");
        })->orderBy('id', $request->order === 'asc' ? 'asc' : 'desc')->get();

        return inertia('Customer/Trash', [
            'customers' => $customers,
            'query' => $request->query()
        ]);
    }

    public function restore(string $id)
    {
        Customer::onlyTrashed()->findOrFail($id)->restore();

        return back();
    }

    public function forceDestroy(string $id)
    {
        $customer = Customer::onlyTrashed()->findOrFail($id);

        if ($customer->image) {
            Storage::disk('public')->delete($customer->image);
        }

        $customer->forceDelete();

        return back();
    }
}

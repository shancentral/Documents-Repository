<?php

namespace App\Http\Controllers;

use App\Model\Customer;
use Illuminate\Http\Request;

class CustomersController extends Controller
{
	public function __construct()
    {
        $this->middleware('auth');
	}
	
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
		$customers = Customer::orderBy('name', 'asc')->paginate(30);
		$index = $customers->firstItem();

		return view('customers.index', compact('index', 'customers'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
		$customer = new Customer();
		$action = "add";

        return view('customers.add_edit', compact('action', 'customer'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

		$request->validate([
			'name' => 'required',
		]);

        $customer = new Customer();

		$customer->name = $request->name;
		$customer->description = $request->description;

		$customer->save();
		return redirect('customers')->with('success', 'Customer saved Successfully');

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function show(Customer $customer)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function edit(Customer $customer)
    {
		$action = "edit";
        return view('customers.add_edit', compact('action', 'customer'));
	}
	
	public function info(Request $request)
    {
		$action = "info";
		$customer = Customer::find($request->id)->first();

        return view('customers.add_edit', compact('action', 'customer'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Customer $customer)
    {
		$request->validate([
			'name' => 'required',
		]);

        $customer->name = $request->name;
		$customer->description = $request->description;

		$customer->save();
		return redirect('customers')->with('success', 'Customer saved Successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function destroy(Customer $customer)
    {
		$customer->delete();
		return redirect('customers')->with('success', 'Customer deleted Successfully');
    }
}

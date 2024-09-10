<?php

namespace App\Http\Controllers\Admin;

use App\Entity\Region;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\region\UpdateRequest;
use Illuminate\Http\Request;

class RegionsController extends Controller
{

    public function index(Request $request)
    {
        $regions = region::orderBy('name')->paginate(30);

        return view('admin.regions.index', compact('regions'));
    }

    public function create(Request $request)
    {
        return view('admin.regions.create');
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => ['required', 'string', 'max:255', 'unique:regions,name,NULL,id,parent_id' . ($request['parent'] ?: 'NULL')],
            'slug' => ['required', 'string', 'max:255', 'unique:regions,name,NULL,id,parent_id' . ($request['parent'] ?: 'NULL')],
            'parent_id' => 'optional|exists:regions,id'
        ]);

        $region = Region::create(
            [
                'name' => $request['name'],
                'email' => $request['email'],
                'parent_id' => $request['parent'],
            ]
        );

        return redirect()->route('admin.regions.show', $region);
    }

    public function show(Region $region)
    {
        return view('admin.regions.show', compact('region'));
    }

    public function edit(Region $region)
    {
        return view('admin.regions.edit', compact('region'));
    }

    public function update(Request $request, Region $region)
    {
        $this->validate($request, [
            'name' => "required|string|max:255|unique:regions,name,$region->id,id,parent_id,$region->parent_id",
            'slug' => "required|string|max:255|unique:regions,name,$region->id,id,parent_id,$region->parent_id",
        ]);

        $region->update($request->only(['name', 'slug']));

        return redirect()->route('admin.regions.show', $region);
    }

    public function destroy(Region $region)
    {
        $region->delete();

        return redirect()->route('admin.regions.index');
    }
}

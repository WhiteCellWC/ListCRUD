<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Category\Services\Implementations\CategoryCommand;
use Modules\Category\Services\Implementations\CategoryQuery;

class CategoryController extends Controller
{
    public function __construct(protected CategoryQuery $query, protected CategoryCommand $command) {}

    public function index(Request $request) {}

    public function store(Request $request) {}

    public function create(Request $request) {}

    public function edit(Request $request) {}

    public function update(Request $request) {}

    public function destroy(Request $request) {}
}

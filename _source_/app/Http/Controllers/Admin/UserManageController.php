<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserManageController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $userCollection = User::orderBy('created_at', 'desc');
        $searchableFields = ['phone' => 'phone', 'name' => 'name'];
        foreach ($searchableFields as $dbField => $queryParam) {
            if ($request->get($queryParam)) {
                $userCollection->where($dbField, 'like', sprintf('%%%s%%', $request->get($queryParam)));
            }
        }
        return response()->json(
            $userCollection->paginate(10)
        );
    }
}

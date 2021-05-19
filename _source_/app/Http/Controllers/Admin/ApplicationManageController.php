<?php
namespace App\Http\Controllers\Admin;

use App\Events\ApplicationApproved;
use App\Http\Controllers\Controller;
use App\Models\Application;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ApplicationManageController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        return response()->json(
            Application::orderBy('created_at')->simplePaginate(10)
        );
    }

    public function approve($id): JsonResponse
    {
        /** @var Application $application */
        $application = Application::find($id);
        if (!$application && !is_null($application->approved)) {
            return response()->json(['message' => 'Unable to update application status'], 422);
        }

        $application->approved();

        event(new ApplicationApproved($application));

        return response()->json(['message' => 'Application approved successfully']);
    }

    public function reject($id): JsonResponse
    {
        /** @var Application $application */
        $application = Application::find($id);
        if (!$application && !is_null($application->approved)) {
            return response()->json(['message' => 'Unable to update application status'], 422);
        }

        $application->reject();

        return response()->json(['message' => 'Application rejected successfully']);
    }
}

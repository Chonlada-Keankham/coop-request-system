<?php

namespace App\Http\Controllers;

use App\Models\CoopRequest;
use Illuminate\Http\Request;

class CoopRequestController extends Controller
{
    public function store(Request $request)
    {
        if ($request->user()->role !== 'public') {
            return response()->json([
                'success' => false,
                'message' => 'Only public users can submit requests',
            ], 403);
        }

        $validated = $request->validate([
            'coop_name' => 'required|string|max:255|unique:coop_requests,coop_name',
            'member_count' => 'required|integer|min:10',
        ]);

        $coopRequest = CoopRequest::create([
            'user_id' => $request->user()->id,
            'coop_name' => $validated['coop_name'],
            'member_count' => $validated['member_count'],
            'status' => 'pending',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Cooperative request submitted successfully',
            'data' => $coopRequest,
        ], 201);
    }

    public function myRequests(Request $request)
    {
        if ($request->user()->role !== 'public') {
            return response()->json([
                'success' => false,
                'message' => 'Only public users can view their requests',
            ], 403);
        }

        $requests = CoopRequest::where('user_id', $request->user()->id)
            ->latest()
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'My cooperative requests retrieved successfully',
            'data' => $requests,
        ]);
    }

    public function allRequests(Request $request)
    {
        if ($request->user()->role !== 'staff') {
            return response()->json([
                'success' => false,
                'message' => 'Only staff users can view all requests',
            ], 403);
        }

        $query = CoopRequest::query();

        if ($request->filled('status')) {
            $request->validate([
                'status' => 'in:pending,approved,rejected'
            ]);

            $query->where('status', $request->status);
        }

        $requests = $query->latest()->get();

        $message = $requests->isEmpty()
            ? 'No requests found'
            : 'Requests retrieved successfully';

        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $requests,
        ]);
    }
}

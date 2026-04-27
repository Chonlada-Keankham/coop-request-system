<?php

namespace App\Http\Controllers;

use App\Models\CoopRequest;
use Illuminate\Http\Request;

class CoopRequestController extends Controller
{
    // Allow public users to submit a cooperative establishment request.
    public function store(Request $request)
    {
        // Only public users are allowed to create requests.
        if ($request->user()->role !== 'public') {
            return response()->json([
                'success' => false,
                'message' => 'Only public users can submit requests',
                'errors' => null,
            ], 403);
        }
        // Validate required business rules:
        // - cooperative name must be unique
        // - initial member count must be at least 10
        $validated = $request->validate([
            'coop_name' => 'required|string|max:255|unique:coop_requests,coop_name',
            'member_count' => 'required|integer|min:10',
        ]);
        // Create request with default pending status.
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
    // Allow public users to view only their own requests.
    public function myRequests(Request $request)
    {
        // Staff users are not allowed to access public user's request list.
        if ($request->user()->role !== 'public') {
            return response()->json([
                'success' => false,
                'message' => 'Only public users can view their requests',
                'errors' => null,
            ], 403);
        }
        // Restrict query by authenticated user's ID.
        $requests = CoopRequest::where('user_id', $request->user()->id)
            ->latest()
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'My cooperative requests retrieved successfully',
            'data' => $requests,
        ], 200);
    }

    // Allow staff users to view all requests and filter by status.
    public function allRequests(Request $request)
    {
        // Optional status filter: pending, approved, or rejected.
        if ($request->user()->role !== 'staff') {
            return response()->json([
                'success' => false,
                'message' => 'Only staff users can view all requests',
                'errors' => null,
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
        // Empty result is still a successful response.
        $message = $requests->isEmpty()
            ? 'No requests found'
            : 'Requests retrieved successfully';

        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $requests,
        ], 200);
    }
    // Allow staff users to approve or reject a pending request.
    public function review(Request $request)
    {
        // Only staff users can review requests.
        if ($request->user()->role !== 'staff') {
            return response()->json([
                'success' => false,
                'message' => 'Only staff users can review requests',
                'errors' => null,
            ], 403);
        }
        // Validate review input from query params/body:
        // - request_id identifies the cooperative request
        // - status must be approved or rejected
        // - note is required as the review reason
        $validated = $request->validate([
            'request_id' => 'required|integer|exists:coop_requests,id',
            'status' => 'required|in:approved,rejected',
            'note' => 'required|string|max:1000',
        ]);

        $coopRequest = CoopRequest::findOrFail($validated['request_id']);
        // Requests that have already been reviewed cannot be reviewed again.
        if ($coopRequest->status !== 'pending') {
            return response()->json([
                'success' => false,
                'message' => 'This request has already been reviewed',
                'errors' => null,
            ], 400);
        }
        // Update request review result and staff note.
        $coopRequest->update([
            'status' => $validated['status'],
            'note' => $validated['note'],
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Request reviewed successfully',
            'data' => $coopRequest,
        ], 200);
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\Request;

class UserController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * Display user management dashboard with filtering
     */
    public function index(Request $request)
    {
        $filters = $this->getFilters($request);

        $users = $this->userService->getUsers($filters);
        $stats = $this->userService->getUserStatistics();

        return view('admin.users.index', compact('users', 'stats', 'filters'));
    }

    /**
     * Display individual user detail
     */
    public function show(User $user)
    {
        $data = $this->userService->getUserDetails($user);

        return view('admin.users.show', [
            'user' => $data['user'],
            'userStats' => $data['userStats'],
            'recentOrders' => $data['recentOrders'],
        ]);
    }

    /**
     * Display admin users list
     */
    public function admins(Request $request)
    {
        $filters = [
            'search' => $request->search,
            'per_page' => $request->input('per_page', 15),
        ];

        $admins = $this->userService->getAdmins($filters);

        return view('admin.users.admins', compact('admins', 'filters'));
    }

    /**
     * Display customer users list
     */
    public function customers(Request $request)
    {
        $filters = $this->getFilters($request);

        $customers = $this->userService->getCustomers($filters);
        $stats = $this->userService->getCustomerStatistics();

        return view('admin.users.customers', compact('customers', 'stats', 'filters'));
    }

    /**
     * Get filters from request
     */
    private function getFilters(Request $request): array
    {
        return [
            'role' => $request->role,
            'email_verified' => $request->email_verified,
            'date_from' => $request->date_from,
            'date_to' => $request->date_to,
            'search' => $request->search,
            'sort_by' => $request->input('sort_by', 'created_at'),
            'sort_order' => $request->input('sort_order', 'desc'),
            'per_page' => $request->input('per_page', 15),
        ];
    }
}

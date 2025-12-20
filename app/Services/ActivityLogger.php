<?php

namespace App\Services;

use App\Models\UserActivityLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class ActivityLogger
{
    /**
     * Log an activity
     */
    public function log(
        string $eventType,
        string $description,
        array $properties = [],
        ?int $userId = null
    ): UserActivityLog {
        return UserActivityLog::create([
            'user_id' => $userId ?? Auth::id(),
            'session_id' => session()->getId(),
            'event_type' => $eventType,
            'description' => $description,
            'properties' => empty($properties) ? null : $properties,
            'ip_address' => Request::ip(),
            'user_agent' => Request::userAgent(),
        ]);
    }

    /**
     * Log login event
     */
    public function logLogin(?int $userId = null): void
    {
        $this->log(
            UserActivityLog::EVENT_LOGIN,
            'User logged in',
            [],
            $userId
        );
    }

    /**
     * Log logout event
     */
    public function logLogout(?int $userId = null): void
    {
        $this->log(
            UserActivityLog::EVENT_LOGOUT,
            'User logged out',
            [],
            $userId
        );
    }

    /**
     * Log profile update
     */
    public function logProfileUpdate(array $changes): void
    {
        $this->log(
            UserActivityLog::EVENT_PROFILE_UPDATE,
            'User updated profile',
            ['changes' => $changes]
        );
    }

    /**
     * Log password change
     */
    public function logPasswordChange(): void
    {
        $this->log(
            UserActivityLog::EVENT_PASSWORD_CHANGE,
            'User changed password'
        );
    }

    /**
     * Log order placement
     */
    public function logOrderPlaced(string $orderNumber, float $total): void
    {
        $this->log(
            UserActivityLog::EVENT_ORDER_PLACED,
            "Placed order {$orderNumber}",
            [
                'order_number' => $orderNumber,
                'total' => $total,
            ]
        );
    }

    /**
     * Log cart addition
     */
    public function logCartAdd(int $productId, string $productName, int $quantity): void
    {
        $this->log(
            UserActivityLog::EVENT_CART_ADD,
            "Added {$productName} to cart",
            [
                'product_id' => $productId,
                'product_name' => $productName,
                'quantity' => $quantity,
            ]
        );
    }

    /**
     * Log cart update
     */
    public function logCartUpdate(int $productId, string $productName, int $oldQuantity, int $newQuantity): void
    {
        $this->log(
            UserActivityLog::EVENT_CART_UPDATE,
            "Updated {$productName} quantity in cart",
            [
                'product_id' => $productId,
                'product_name' => $productName,
                'old_quantity' => $oldQuantity,
                'new_quantity' => $newQuantity,
            ]
        );
    }

    /**
     * Log cart removal
     */
    public function logCartRemove(int $productId, string $productName): void
    {
        $this->log(
            UserActivityLog::EVENT_CART_REMOVE,
            "Removed {$productName} from cart",
            [
                'product_id' => $productId,
                'product_name' => $productName,
            ]
        );
    }

    /**
     * Log address creation
     */
    public function logAddressCreate(string $label): void
    {
        $this->log(
            UserActivityLog::EVENT_ADDRESS_CREATE,
            "Created new address: {$label}",
            ['label' => $label]
        );
    }

    /**
     * Log address update
     */
    public function logAddressUpdate(string $label): void
    {
        $this->log(
            UserActivityLog::EVENT_ADDRESS_UPDATE,
            "Updated address: {$label}",
            ['label' => $label]
        );
    }

    /**
     * Log address deletion
     */
    public function logAddressDelete(string $label): void
    {
        $this->log(
            UserActivityLog::EVENT_ADDRESS_DELETE,
            "Deleted address: {$label}",
            ['label' => $label]
        );
    }

    /**
     * Log address set as default
     */
    public function logAddressSetDefault(string $label): void
    {
        $this->log(
            UserActivityLog::EVENT_ADDRESS_SET_DEFAULT,
            "Set {$label} as default address",
            ['label' => $label]
        );
    }
}

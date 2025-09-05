# Shopping Cart System API Documentation

## Base URL
```
http://your-domain.com/api/v1
```

## Authentication
The API uses Laravel Sanctum for authentication. Include the Bearer token in the Authorization header:
```
Authorization: Bearer {your-token}
```

## Response Format
All API responses follow this format:
```json
{
    "success": true|false,
    "message": "Response message",
    "data": {} // Response data (optional)
}
```

## Error Responses
Error responses include additional details:
```json
{
    "success": false,
    "message": "Error message",
    "errors": {
        "field": ["Validation error message"]
    }
}
```

---

## Authentication Endpoints

### POST /auth/login
Login user and get access token.

**Request Body:**
```json
{
    "email": "user@example.com",
    "password": "password123",
    "remember": false
}
```

**Response:**
```json
{
    "success": true,
    "message": "Login successful",
    "data": {
        "user": {
            "id": 1,
            "name": "John Doe",
            "email": "user@example.com",
            "email_verified_at": "2024-01-01T00:00:00.000000Z"
        },
        "token": "1|abc123...",
        "token_type": "Bearer"
    }
}
```

### POST /auth/register
Register new user.

**Request Body:**
```json
{
    "name": "John Doe",
    "email": "user@example.com",
    "password": "password123",
    "password_confirmation": "password123"
}
```

### POST /auth/logout
Logout user and revoke token. **Requires authentication.**

### POST /auth/refresh
Refresh access token. **Requires authentication.**

### GET /auth/me
Get authenticated user profile. **Requires authentication.**

### POST /auth/password/reset
Send password reset link.

**Request Body:**
```json
{
    "email": "user@example.com"
}
```

### POST /auth/password/reset/token
Reset password with token.

**Request Body:**
```json
{
    "token": "reset-token",
    "email": "user@example.com",
    "password": "newpassword123",
    "password_confirmation": "newpassword123"
}
```

---

## Member Authentication Endpoints

### POST /member/auth/login
Login member and get access token.

**Request Body:**
```json
{
    "email": "member@example.com",
    "password": "password123",
    "remember": false
}
```

**Response:**
```json
{
    "success": true,
    "message": "Login successful",
    "data": {
        "member": {
            "id": 1,
            "name": "John Doe",
            "email": "member@example.com",
            "membership_type": "basic",
            "membership_status": "active",
            "points_balance": "100.00",
            "total_spent": "500.00"
        },
        "token": "1|abc123...",
        "token_type": "Bearer"
    }
}
```

### POST /member/auth/register
Register new member.

**Request Body:**
```json
{
    "name": "John Doe",
    "email": "member@example.com",
    "password": "password123",
    "password_confirmation": "password123",
    "phone": "+1234567890",
    "date_of_birth": "1990-01-01",
    "gender": "male",
    "address": "123 Main St",
    "city": "New York",
    "state": "NY",
    "postal_code": "10001",
    "country": "United States"
}
```

### POST /member/auth/logout
Logout member and revoke token. **Requires authentication.**

### POST /member/auth/refresh
Refresh member access token. **Requires authentication.**

### GET /member/auth/me
Get authenticated member profile. **Requires authentication.**

### PUT /member/profile
Update member profile. **Requires authentication.**

**Request Body:**
```json
{
    "name": "John Doe",
    "email": "newemail@example.com",
    "phone": "+1234567890",
    "address": "456 New St",
    "city": "Los Angeles",
    "state": "CA"
}
```

### GET /member/benefits
Get member's membership benefits. **Requires authentication.**

**Response:**
```json
{
    "success": true,
    "data": {
        "membership_level": "silver",
        "membership_type": "premium",
        "benefits": ["priority_support", "newsletter", "free_shipping", "early_access"],
        "points_balance": "100.00",
        "total_spent": "500.00"
    }
}
```

### GET /member/orders
Get member's order history. **Requires authentication.**

### POST /member/auth/password/reset
Send password reset link for member.

**Request Body:**
```json
{
    "email": "member@example.com"
}
```

### POST /member/auth/password/reset/token
Reset member password with token.

**Request Body:**
```json
{
    "token": "reset-token",
    "email": "member@example.com",
    "password": "newpassword123",
    "password_confirmation": "newpassword123"
}
```

---

## Product Endpoints

### GET /products
Get products list with search and filters.

**Query Parameters:**
- `search` - Search by name or description
- `category_id` - Filter by category
- `min_price` - Minimum price filter
- `max_price` - Maximum price filter
- `in_stock` - Filter by stock availability (true/false)
- `featured` - Filter featured products (true/false)
- `sort_by` - Sort field (name, price, created_at, updated_at)
- `sort_order` - Sort order (asc, desc)
- `per_page` - Items per page (max 50)

**Example:**
```
GET /products?search=laptop&category_id=1&min_price=100&max_price=1000&sort_by=price&sort_order=asc&per_page=20
```

### GET /products/{id}
Get single product details.

### GET /products/featured
Get featured products.

**Query Parameters:**
- `limit` - Number of products (max 20)

### GET /products/search
Search products.

**Query Parameters:**
- `q` - Search query (required, min 2 characters)
- `category_id` - Filter by category
- `per_page` - Items per page (max 50)

### GET /products/{id}/related
Get related products.

**Query Parameters:**
- `limit` - Number of products (max 10)

### GET /products/categories
Get product categories.

---

## Cart Endpoints

### GET /cart
Get cart contents.

### POST /cart/add
Add product to cart.

**Request Body:**
```json
{
    "product_id": 1,
    "quantity": 2
}
```

### PUT /cart/items/{cartItemId}
Update cart item quantity.

**Request Body:**
```json
{
    "quantity": 3
}
```

### DELETE /cart/items/{cartItemId}
Remove item from cart.

### DELETE /cart/clear
Clear entire cart.

### POST /cart/coupon/apply
Apply coupon to cart.

**Request Body:**
```json
{
    "coupon_code": "SAVE10"
}
```

### DELETE /cart/coupon/remove
Remove coupon from cart.

---

## User Endpoints (Requires Authentication)

### GET /user/profile
Get user profile.

### PUT /user/profile
Update user profile.

**Request Body:**
```json
{
    "name": "John Doe",
    "email": "newemail@example.com"
}
```

### GET /user/addresses
Get user addresses.

### POST /user/addresses
Create new address.

**Request Body:**
```json
{
    "type": "shipping",
    "first_name": "John",
    "last_name": "Doe",
    "company": "Company Name",
    "address_line_1": "123 Main St",
    "address_line_2": "Apt 4B",
    "city": "New York",
    "state": "NY",
    "postal_code": "10001",
    "country": "United States",
    "phone": "+1234567890"
}
```

### PUT /user/addresses/{addressId}
Update address.

### DELETE /user/addresses/{addressId}
Delete address.

### GET /user/coupons
Get available coupons for user.

### GET /user/orders
Get user orders.

---

## Order Endpoints (Requires Authentication)

### GET /orders
Get user orders.

### GET /orders/{orderId}
Get single order details.

### POST /orders
Create new order.

**Request Body:**
```json
{
    "payment_method": "credit_card",
    "shipping_address": {
        "first_name": "John",
        "last_name": "Doe",
        "company": "Company Name",
        "address_line_1": "123 Main St",
        "address_line_2": "Apt 4B",
        "city": "New York",
        "state": "NY",
        "postal_code": "10001",
        "country": "United States",
        "phone": "+1234567890"
    },
    "billing_address": {
        "first_name": "John",
        "last_name": "Doe",
        "company": "Company Name",
        "address_line_1": "123 Main St",
        "address_line_2": "Apt 4B",
        "city": "New York",
        "state": "NY",
        "postal_code": "10001",
        "country": "United States",
        "phone": "+1234567890"
    },
    "tax_amount": 10.00,
    "shipping_amount": 5.00,
    "notes": "Please deliver during business hours"
}
```

### PUT /orders/{orderId}/cancel
Cancel order.

---

## Status Codes

- `200` - Success
- `201` - Created
- `400` - Bad Request
- `401` - Unauthorized
- `403` - Forbidden
- `404` - Not Found
- `422` - Validation Error
- `500` - Internal Server Error

---

## Rate Limiting

API endpoints are rate limited to prevent abuse. Default limits:
- Authentication endpoints: 5 requests per minute
- Other endpoints: 60 requests per minute

---

## Examples

### Complete Shopping Flow

1. **Register/Login:**
```bash
curl -X POST http://your-domain.com/api/v1/auth/register \
  -H "Content-Type: application/json" \
  -d '{"name":"John Doe","email":"john@example.com","password":"password123","password_confirmation":"password123"}'
```

2. **Get Products:**
```bash
curl -X GET http://your-domain.com/api/v1/products?featured=true
```

3. **Add to Cart:**
```bash
curl -X POST http://your-domain.com/api/v1/cart/add \
  -H "Content-Type: application/json" \
  -d '{"product_id":1,"quantity":2}'
```

4. **Apply Coupon:**
```bash
curl -X POST http://your-domain.com/api/v1/cart/coupon/apply \
  -H "Content-Type: application/json" \
  -d '{"coupon_code":"SAVE10"}'
```

5. **Create Order:**
```bash
curl -X POST http://your-domain.com/api/v1/orders \
  -H "Authorization: Bearer {token}" \
  -H "Content-Type: application/json" \
  -d '{"payment_method":"credit_card","shipping_address":{...}}'
```

---

## Notes

- All timestamps are in ISO 8601 format
- Prices are returned as numbers (not strings)
- Formatted prices are included for display purposes
- Cart operations work for both authenticated users and guests
- Guest carts are session-based and will be lost when session expires
- All endpoints return consistent JSON responses
- Pagination is included for list endpoints
- Search and filtering are available on relevant endpoints

# Shopping Cart Implementation

This document describes the shopping cart Blade template implementation that integrates with your Laravel API endpoints.

## Features

### 🛒 Shopping Cart Features
- **Dynamic Cart Loading**: Fetches cart data from `/api/v1/cart` endpoint
- **Real-time Updates**: Updates quantities, removes items, and applies coupons
- **Responsive Design**: Works on desktop, tablet, and mobile devices
- **Bootstrap 5 Styling**: Modern, clean interface
- **Swiper Integration**: Related products carousel
- **Toast Notifications**: User feedback for all actions

### 🔧 Technical Features
- **jQuery Integration**: Smooth interactions and AJAX calls
- **CSRF Protection**: Secure API communication
- **Error Handling**: Graceful error handling with user feedback
- **Loading States**: Visual feedback during API calls
- **Empty State**: User-friendly empty cart display

## API Integration

The cart integrates with the following API endpoints:

### Cart Management
- `GET /api/v1/cart` - Load cart data
- `POST /api/v1/cart/add` - Add product to cart
- `PUT /api/v1/cart/items/{cartItem}` - Update item quantity
- `DELETE /api/v1/cart/items/{cartItem}` - Remove item from cart
- `DELETE /api/v1/cart/clear` - Clear entire cart

### Coupon Management
- `POST /api/v1/cart/coupon/apply` - Apply coupon code
- `DELETE /api/v1/cart/coupon/remove` - Remove applied coupon

### Related Products
- `GET /api/v1/products?limit=6` - Load related products

## File Structure

```
resources/views/
├── cart.blade.php          # Main shopping cart page
├── products.blade.php      # Products listing page (placeholder)
└── checkout.blade.php      # Checkout page (placeholder)

routes/
└── web.php                 # Web routes including cart, products, checkout
```

## Usage

### 1. Access the Cart
Visit `/cart` in your browser to see the shopping cart interface.

### 2. Cart Operations
- **Update Quantity**: Use +/- buttons or type directly in quantity input
- **Remove Items**: Click the trash icon to remove individual items
- **Apply Coupons**: Enter coupon code and click "Apply"
- **Clear Cart**: Click "Clear Cart" to remove all items
- **Continue Shopping**: Click "Continue Shopping" to browse products

### 3. Related Products
The cart displays related products in a Swiper carousel at the bottom of the page.

## Customization

### Styling
The cart uses Bootstrap 5 classes and custom CSS. You can modify the styles in the `<style>` section of `cart.blade.php`.

### API Endpoints
Update the `apiBaseUrl` in the JavaScript ShoppingCart class if your API endpoints are different.

### Toast Notifications
Customize toast messages and styling by modifying the `showToast()` method.

## Dependencies

### CDN Resources
- **Bootstrap 5.3.0**: CSS and JavaScript framework
- **jQuery 3.7.1**: JavaScript library for DOM manipulation
- **Font Awesome 6.4.0**: Icon library
- **Swiper 10**: Touch slider for related products

### Laravel Requirements
- Laravel 11.0+
- CSRF token support
- API routes configured in `routes/api.php`

## Browser Support

- Chrome 90+
- Firefox 88+
- Safari 14+
- Edge 90+

## Security Features

- CSRF token validation for all POST/PUT/DELETE requests
- Input validation and sanitization
- Secure API communication
- XSS protection through proper escaping

## Performance Optimizations

- Lazy loading of related products
- Efficient DOM updates
- Minimal API calls
- Optimized image loading

## Future Enhancements

### Planned Features
- [ ] Guest cart persistence using localStorage
- [ ] Cart item wishlist functionality
- [ ] Product recommendations based on cart contents
- [ ] Real-time cart synchronization
- [ ] Advanced shipping calculator
- [ ] Multiple payment methods integration

### API Enhancements
- [ ] Cart item validation
- [ ] Inventory checking
- [ ] Price updates
- [ ] Tax calculation
- [ ] Shipping options

## Troubleshooting

### Common Issues

1. **Cart not loading**: Check API endpoint configuration and CORS settings
2. **CSRF token errors**: Ensure CSRF token is properly set in meta tag
3. **Styling issues**: Verify Bootstrap 5 CSS is loading correctly
4. **JavaScript errors**: Check browser console for API response errors

### Debug Mode
Enable debug mode by adding `console.log()` statements in the ShoppingCart class methods.

## Support

For issues or questions about the shopping cart implementation, please check:
1. Laravel logs in `storage/logs/laravel.log`
2. Browser developer console for JavaScript errors
3. Network tab for API request/response details

---

**Note**: This is a frontend implementation that requires your Laravel API endpoints to be properly configured and functional.


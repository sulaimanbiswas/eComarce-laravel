<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductReviewRequest;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Services\OrderAccessService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Spatie\ResponseCache\Facades\ResponseCache;

class ReviewController extends Controller
{
    /**
     * Store a new review for a product.
     */
    public function store(ProductReviewRequest $request, Product $product, OrderAccessService $orderAccess): RedirectResponse
    {
        $data = $request->validated();
        $order = Order::find($data['order_id']);
        if (! $order || ! $orderAccess->phoneMatches($order, $data['phone_number'])) {
            throw ValidationException::withMessages(['order_id' => 'The order ID and mobile number do not match.']);
        }

        $containsProduct = collect($order->products)->contains(fn ($item): bool => (int) data_get($item, 'id') === (int) $product->id || (int) data_get($item, 'parent_id') === (int) $product->id);
        if (! $containsProduct) {
            throw ValidationException::withMessages(['order_id' => 'This order does not contain the selected product.']);
        }

        $user = $order->user ?? User::firstOrCreate(
            ['phone_number' => $order->phone],
            ['name' => $order->name, 'password' => bcrypt(str()->random(32)), 'is_active' => true],
        );
        if ($product->reviews()->where('user_id', $user->id)->exists()) {
            throw ValidationException::withMessages(['review' => 'You have already reviewed this product.']);
        }

        $product->addReview([
            'review' => $data['review'],
            'department' => 'default',
            'recommend' => $request->boolean('recommend'),
            'approved' => false,
            'ratings' => ['overall' => (int) $data['rating']],
        ], $user->id);

        if (config('responsecache.enabled', false)) {
            ResponseCache::forget(route('products.show', $product->slug));
            ResponseCache::forget(route('products.index'));
        }

        return redirect()
            ->to(route('products.show', $product->slug).'?review_submitted='.time().'#review-form-container')
            ->with('success', 'Your review has been submitted and is pending approval.')
            ->with('review_submitted', true)
            ->header('Cache-Control', 'no-cache, no-store, must-revalidate');
    }

    /**
     * Get paginated reviews for a product.
     */
    public function index(Request $request, Product $product)
    {
        $perPage = $request->get('per_page', 10);
        $page = $request->get('page', 1);

        // Get approved reviews with ratings using the relationship directly
        $reviews = $product->reviews()
            ->where('approved', true)
            ->with('user:id,name', 'ratings')
            ->orderBy('created_at', 'desc')
            ->paginate($perPage, ['*'], 'page', $page);

        if ($request->expectsJson()) {
            return response()->json([
                'reviews' => $reviews->map(function ($review) {
                    return [
                        'id' => $review->id,
                        'review' => $review->review,
                        'recommend' => $review->recommend,
                        'created_at' => $review->created_at->diffForHumans(),
                        'user' => $review->user ? [
                            'id' => $review->user->id,
                            'name' => $review->user->name,
                        ] : null,
                        'user_id' => $review->user_id,
                        'ratings' => $review->ratings->map(function ($rating) {
                            return [
                                'key' => $rating->key,
                                'value' => $rating->value,
                            ];
                        }),
                    ];
                }),
                'pagination' => [
                    'current_page' => $reviews->currentPage(),
                    'last_page' => $reviews->lastPage(),
                    'per_page' => $reviews->perPage(),
                    'total' => $reviews->total(),
                    'has_more' => $reviews->hasMorePages(),
                ],
            ]);
        }

        // return view('products.reviews', compact('product', 'reviews'));
    }
}

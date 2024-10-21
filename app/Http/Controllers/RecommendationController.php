<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Rating;
use Illuminate\Http\Request;

class RecommendationController extends Controller
{
    public function recommend($productId)
    {
        $targetRatings = Rating::where('product_id', $productId)->get();
        $allProducts = Product::where('id', '!=', $productId)->get();
        dd($allProducts);

        $similarities = [];

        foreach ($allProducts as $product) {
            $otherRatings = Rating::where('product_id', $product->id)->get();
            $similarity = $this->cosineSimilarity($targetRatings, $otherRatings);
            $similarities[$product->id] = $similarity;
        }

        // Sort products by similarity in descending order
        arsort($similarities);
        // dd($similarities);

        // Get the top 5 recommendations
        $recommendedProducts = array_slice(array_keys($similarities), 0, 5);

        return Product::find($recommendedProducts);
    }

    private function cosineSimilarity($ratingsA, $ratingsB)
    {
        $dotProduct = $sumA = $sumB = 0;

        foreach ($ratingsA as $index => $ratingA) {
            $ratingB = $ratingsB[$index] ?? null;
            if ($ratingB) {
                $dotProduct += $ratingA->rating * $ratingB->rating;
                $sumA += pow($ratingA->rating, 2);
                $sumB += pow($ratingB->rating, 2);
            }
        }

        return $sumA && $sumB ? $dotProduct / (sqrt($sumA) * sqrt($sumB)) : 0;
    }
}
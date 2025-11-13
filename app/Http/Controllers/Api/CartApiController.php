namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

class CartApiController extends Controller
{
    public function getTotal()
    {
        $cart = session('cart', []);
        $total = 0;

        foreach ($cart as $item) {
            $total += $item['harga'] * $item['quantity'];
        }

        return response()->json(['total' => $total]);
    }
}

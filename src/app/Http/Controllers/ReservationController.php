<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReservationRequest;
use Illuminate\Http\Request;
use App\Models\Reservation;
use App\Models\Shop;

class ReservationController extends Controller
{
    public function makeReservation(ReservationRequest $request, $shop_id)
    {
        $now = now();
        $reservationDateTime = \Carbon\Carbon::parse($request->date . ' ' . $request->time);

        if ($reservationDateTime < $now) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['time' => '過去の日時は選択できません。']);
        }

        $reservation = Reservation::create([
            'user_id' => auth()->id(),
            'shop_id' => $shop_id,
            'date' => $request->input('date'),
            'time' => $request->input('time'),
            'number_of_people' => $request->input('number_of_people'),
        ]);

        $reservation->generateQrCode();

        return redirect()->route('reservation.show')->with('success', '予約が完了しました。');
    }

    public function deleteReservation(Request $request, $reservation_id)
    {
        $reservation = Reservation::findOrFail($reservation_id);

        if ($reservation->user_id !== auth()->id()) {
            return redirect()->route('mypage')->with('error', '予約の削除権限がありません。');
        }

        $reservation->delete();

        return redirect()->route('mypage')->with('success', '予約が削除されました。');
    }

    public function showReservation()
    {
        return view('reservation_complete');
    }

    public function updateReservation(ReservationRequest $request, $reservation_id)
    {
        $reservation = Reservation::findOrFail($reservation_id);

        if ($reservation->user_id !== auth()->id()) {
            return redirect()->route('mypage')->with('error', '予約の編集権限がありません。');
        }

        $now = now();

        $validated = $request->validate([
            'date' => [
                'required',
                'date',
                'after_or_equal:today',
            ],
            'time' => [
                'required',
                'date_format:H:i',
                function ($attribute, $value, $fail) use ($request, $now) {
                    $reservationDateTime = \Carbon\Carbon::parse($request->date . ' ' . $value);
                    if ($reservationDateTime < $now) {
                        $fail('過去の日時は選択できません。');
                    }
                },
            ],
            'number_of_people' => 'required|integer|min:1|max:10',
        ]);

        $reservation->update([
            'date' => $validated['date'],
            'time' => $validated['time'],
            'number_of_people' => $validated['number_of_people'],
        ]);

        $reservation->generateQrCode();

        return redirect()->route('mypage')->with('success', '予約が更新されました。');
    }

    public function showReviewForm(Reservation $reservation)
    {
        return view('review_form', compact('reservation'));
    }

    public function verify($id)
    {
        try {
            $reservation = Reservation::with(['user', 'shop'])
                ->findOrFail($id);

            return view('reservations.verify', compact('reservation'));
        } catch (\Exception $e) {
            return redirect()->route('mypage')
                ->with('error', '予約情報が見つかりませんでした。');
        }
    }

    public function showHistory()
    {
        $user = auth()->user();
        $past_reservations = $user->reservations()
            ->with(['shop', 'review'])
            ->where(function ($query) {
                $now = now();
                $query->where('date', '<', $now->toDateString())
                    ->orWhere(function ($q) use ($now) {
                        $q->where('date', '=', $now->toDateString())
                            ->where('time', '<', $now->toTimeString());
                    });
            })
            ->orderBy('date', 'desc')
            ->orderBy('time', 'desc')
            ->paginate(5);

        $total_count = $past_reservations->total();

        return view('reservation_history', compact('past_reservations', 'total_count'));
    }
}
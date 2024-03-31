<?php

namespace App\Http\Middleware;

use App\Models\Employer_list;
use App\Models\Scholar_list;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Session;

class CheckPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $mode = Session::get("list_mode");
        $unitno = Session::get("id");
        $table = $mode === "scholar" ? new Scholar_list : new Employer_list;

        if ($request->input("SN") !== null && $unitno !== 0) {
            $sn = $request->input("SN");
            $data = $table->where("SN", $sn)->first();

            if ($data->unitno !== $unitno) {
                return response()->json(['message' => 'Unauthorized action'], 403);
            }
        }

        if ($request->input("sn_list") !== null && $unitno !== 0) {
            $sn_list = $request->input("sn_list");
            $data = $table->whereIn("SN", $sn_list)->get();
            foreach($data as $row) {
                if ($row->unitno !== $unitno) {
                    return response()->json(['message' => 'Unauthorized action'], 403);
                }
            }
        }

        return $next($request);
    }
}

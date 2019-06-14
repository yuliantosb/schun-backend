<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Setting;

class SettingController extends Controller
{
    public function index()
    {
        $setting = Setting::first();
        
        if (empty($setting)) {
            $result = collect([
                'site_name' => 'Shard Dashboard',
                'file' => 'data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iVVRGLTgiPz4KPHN2ZyB3aWR0aD0iNDBweCIgaGVpZ2h0PSI0MHB4IiB2aWV3Qm94PSIwIDAgNDAgNDAiIHZlcnNpb249IjEuMSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB4bWxuczp4bGluaz0iaHR0cDovL3d3dy53My5vcmcvMTk5OS94bGluayI+CiAgICA8IS0tIEdlbmVyYXRvcjogU2tldGNoIDQ4LjEgKDQ3MjUwKSAtIGh0dHA6Ly93d3cuYm9oZW1pYW5jb2RpbmcuY29tL3NrZXRjaCAtLT4KICAgIDx0aXRsZT5Mb2dvIEljb248L3RpdGxlPgogICAgPGRlc2M+Q3JlYXRlZCB3aXRoIFNrZXRjaC48L2Rlc2M+CiAgICA8ZGVmcz48L2RlZnM+CiAgICA8ZyBpZD0iU2hhcmRzLS0tRGFzaGJvYXJkLS0tT3ZlcnZpZXctMSIgc3Ryb2tlPSJub25lIiBzdHJva2Utd2lkdGg9IjEiIGZpbGw9Im5vbmUiIGZpbGwtcnVsZT0iZXZlbm9kZCIgdHJhbnNmb3JtPSJ0cmFuc2xhdGUoLTExOC4wMDAwMDAsIC01NC4wMDAwMDApIj4KICAgICAgICA8ZyBpZD0iU2lkZWJhciIgZmlsbD0iIzAwN0JGRiI+CiAgICAgICAgICAgIDxnIGlkPSJMb2dvIiB0cmFuc2Zvcm09InRyYW5zbGF0ZSgxMTguMDAwMDAwLCA1NC4wMDAwMDApIj4KICAgICAgICAgICAgICAgIDxwYXRoIGQ9Ik0yMS45MzMzMDExLDMwLjMxNDk2NyBMMTguMDY5MTE1NSwxOC44MDM3Njk5IEwyNC41NjQyMDE3LDE0LjkzODc3ODggTDI4LjAxNzU2MDgsMTkuODcyNzI0MyBMMjEuOTMzMzAxMSwzMC4zMTQ5NjcgWiBNMTkuNDA4NzMyMSwzMi42MDkxNTEgTDEyLjY2NjM0NDQsMjEuMTgwMTE5MiBMMTYuNDkwMjUyOSwxOS44NjQ2Njg5IEwyMC4zMTMzNTU5LDMxLjA0NjM5OTIgTDE5LjQwODczMjEsMzIuNjA5MTUxIFogTTIwLjIzMTE5MDYsNy4zOTA4NDkwNCBMMjMuODQ4ODgwMywxMy41OTkxNjIyIEwxMi4xNzMzNTI3LDE5LjgwNjY2OTkgTDIwLjIzMTE5MDYsNy4zOTA4NDkwNCBaIE0yMCwwIEM4Ljk1NDQwNjMyLDAgMCw4Ljk1NDQwNjMyIDAsMjAgQzAsMzEuMDQ1NTkzNyA4Ljk1NDQwNjMyLDQwIDIwLDQwIEMzMS4wNDQ3ODgxLDQwIDM5Ljk5OTE5NDUsMzEuMDQ1NTkzNyAzOS45OTkxOTQ1LDIwIEMzOS45OTkxOTQ1LDguOTU0NDA2MzIgMzEuMDQ0Nzg4MSwwIDIwLDAgTDIwLDAgWiIgaWQ9IkxvZ28tSWNvbiI+PC9wYXRoPgogICAgICAgICAgICA8L2c+CiAgICAgICAgPC9nPgogICAgPC9nPgo8L3N2Zz4=',
            ]);
        } else {
            $result = $setting;
        }

        return response()->json([
            'type' => 'success',
            'data' => $result
        ], 200);
    }

    public function update(Request $request)
    {

        $request->validate([
            'site_name' => 'required',
        ]);

        if (Setting::get()->isEmpty()) {
            $setting = new Setting;
        } else {
            $setting = Setting::first();
        }
        
        $setting->site_name = $request->site_name;

        if (!empty($request->file)) {
            $setting->logo = $request->logo;
            $setting->file = $request->file;
        }

        $setting->currency = $request->currency;
        $setting->thousand_separator = $request->thousand_separator;
        $setting->decimal_separator = $request->decimal_separator;
        $setting->save();

        return response()->json([
            'type' => 'success',
            'message' => 'Setting updated successfully!'
        ], 201);
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\TagNumbersExport;

class TagNumberController extends Controller
{
    /**
     * Display the tag number generator form.
     */
    public function index()
    {
        return view('admin.tag-numbers.index');
    }

    /**
     * Generate tag numbers.
     */
    public function generate(Request $request)
    {
        $validated = $request->validate([
            'category' => ['required', 'in:raw_material,wip,finish_part'],
            'quantity' => ['required', 'integer', 'min:1', 'max:1000'],
            'mode' => ['required', 'in:fresh,continue'],
            'start_from' => ['required_if:mode,continue', 'nullable', 'integer', 'min:1', 'max:9999'],
        ]);

        $category = $request->category;
        $quantity = $request->quantity;
        
        // Map category to prefix
        $prefixes = [
            'raw_material' => 'RM',
            'wip' => 'WIP',
            'finish_part' => 'FP',
        ];
        
        $prefix = $prefixes[$category];
        
        // Determine starting number
        $startFrom = ($request->mode === 'continue' && $request->start_from)
            ? (int) $request->start_from + 1
            : 1;
        
        // Generate tag numbers with format: PREFIX-YYYYMMDD-XXXX
        $date = now()->format('Ymd');
        $tagNumbers = [];
        
        for ($i = $startFrom; $i < $startFrom + $quantity; $i++) {
            $number = str_pad($i, 4, '0', STR_PAD_LEFT);
            $tagNumbers[] = $prefix . '-' . $date . '-' . $number;
        }

        return view('admin.tag-numbers.result', compact('tagNumbers', 'category', 'quantity'));
    }
    
    /**
     * Download tag numbers as Excel.
     */
    public function downloadExcel(Request $request)
    {
        $tagNumbers = json_decode($request->tag_numbers, true);
        $category = $request->category;
        
        $filename = 'tag-numbers-' . now()->format('YmdHis') . '.xlsx';
        
        return Excel::download(new TagNumbersExport($tagNumbers, $category), $filename);
    }
}

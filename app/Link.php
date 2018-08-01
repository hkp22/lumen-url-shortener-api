<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Helpers\Math;
use App\Exceptions\CodeGenerationException;

class Link extends Model
{
    protected $fillable = [
        'original_url',
        'code',
        'requested_count',
        'used_count',
        'last_requested',
        'last_used'
    ];

    protected $dates = [
        'last_requested',
        'last_used'
    ];

    public function touchTimestamp($column)
    {
        $this->{$column} = $this->freshTimestamp();
        $this->save();
    }

    /**
     * Generate base code from id.
     *
     * @return string
     */
    public function getCode()
    {
        if (!$this->id) {
            throw new CodeGenerationException;
        }

        return (new Math)->toBase($this->id);
    }

    /**
     * Get model by code.
     *
     * @param  mixed                                 $code
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public static function byCode($code)
    {
        return static::where('code', $code);
    }

    /**
     * Short Url
     *
     * @return string
     */
    public function shortUrl()
    {
        if (!$this->code) {
            return null;
        }

        return env('CLIENT_URL') . '/' . $this->code;
    }
}

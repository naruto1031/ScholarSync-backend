<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class discordGuild extends Model
{
	use HasFactory;
	protected $primaryKey = 'guild_id';
	public $incrementing = false;
	protected $fillable = ['guild_id', 'class_id'];

	public static function findGuild(string $class_id)
	{
		$guild = self::where('class_id', $class_id)->first();
		return $guild;
	}
}

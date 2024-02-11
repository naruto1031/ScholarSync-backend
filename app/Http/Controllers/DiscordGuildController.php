<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DiscordGuild;

class DiscordGuildController extends Controller
{
	public function findGuild(string $class_id)
	{
		$guild = DiscordGuild::findGuild($class_id);
		return response()->json($guild, 200);
	}
}

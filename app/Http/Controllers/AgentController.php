<?php

namespace App\Http\Controllers;

class AgentController extends Controller
{
    public function agentDashboard()
    {
        return view('agent.agent_dashboard');
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Job;
use App\Models\Deliverable;
use App\Models\Quotation;
use Illuminate\Http\Request;

class CalendarController extends Controller
{
    public function index()
    {
        return view('calendar.index');
    }

    public function events(Request $request)
    {
        $events = [];

        // Jobs - Event dates
        $jobs = Job::with('client')
            ->whereNotNull('event_date')
            ->whereNotIn('status', ['Cancelled'])
            ->get();

        foreach ($jobs as $job) {
            $color = match($job->status) {
                'Confirmed'   => '#1d4ed8',
                'In Progress' => '#d97706',
                'Editing'     => '#7c3aed',
                'Delivered'   => '#0d9488',
                'Completed'   => '#16a34a',
                default       => '#6b7280',
            };

            $events[] = [
                'id'    => 'job-' . $job->id,
                'title' => $job->title . ' (' . $job->client->name . ')',
                'start' => $job->event_date->format('Y-m-d'),
                'color' => $color,
                'url'   => route('jobs.show', $job),
                'extendedProps' => [
                    'type'   => 'Job',
                    'status' => $job->status,
                    'client' => $job->client->name,
                ],
            ];
        }

        // Jobs - Delivery dates
        $jobDeliveries = Job::with('client')
            ->whereNotNull('delivery_date')
            ->whereNotIn('status', ['Cancelled', 'Completed'])
            ->get();

        foreach ($jobDeliveries as $job) {
            $events[] = [
                'id'    => 'job-delivery-' . $job->id,
                'title' => '📦 ' . $job->title . ' delivery',
                'start' => $job->delivery_date->format('Y-m-d'),
                'color' => '#dc2626',
                'url'   => route('jobs.show', $job),
                'extendedProps' => [
                    'type'   => 'Job Delivery',
                    'status' => $job->status,
                    'client' => $job->client->name,
                ],
            ];
        }

        // Deliverables - Due dates
        $deliverables = Deliverable::with('client')
            ->whereNotNull('due_date')
            ->whereNotIn('status', ['Delivered', 'Approved'])
            ->get();

        foreach ($deliverables as $deliverable) {
            $events[] = [
                'id'    => 'deliverable-' . $deliverable->id,
                'title' => '🎯 ' . $deliverable->title,
                'start' => $deliverable->due_date->format('Y-m-d'),
                'color' => '#9333ea',
                'url'   => route('deliverables.show', $deliverable),
                'extendedProps' => [
                    'type'   => 'Deliverable',
                    'status' => $deliverable->status,
                    'client' => $deliverable->client->name,
                ],
            ];
        }

        // Quotations - Valid until
        $quotations = Quotation::with('client')
            ->whereIn('status', ['Sent', 'Draft'])
            ->whereNotNull('valid_until')
            ->get();

        foreach ($quotations as $quotation) {
            $events[] = [
                'id'    => 'quotation-' . $quotation->id,
                'title' => '📄 ' . $quotation->quotation_number . ' expires',
                'start' => $quotation->valid_until->format('Y-m-d'),
                'color' => '#f59e0b',
                'url'   => route('quotations.show', $quotation),
                'extendedProps' => [
                    'type'   => 'Quotation Expiry',
                    'status' => $quotation->status,
                    'client' => $quotation->client->name,
                ],
            ];
        }

        return response()->json($events);
    }
}
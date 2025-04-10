@extends('layouts.admin')

@section('title', 'Notification Settings')

@section('content')
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Notification Settings</h1>
        <p class="mt-1 text-sm text-gray-500">Configure email and system notifications for different events.</p>
    </div>

    <form action="{{ route('admin.settings.notifications.save') }}" method="POST" class="bg-white rounded-lg shadow-sm">
        @csrf
        
        <div class="p-6">
            <h2 class="text-lg font-medium text-gray-900 mb-4">Email Notifications</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">
                <!-- New Rental Notification -->
                <div class="flex items-start">
                    <div class="flex items-center h-5">
                        <input id="email_new_rental" name="email_new_rental" type="checkbox"
                            {{ \App\Models\Setting::getValue('notification.email_new_rental', true) ? 'checked' : '' }}
                            class="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                    </div>
                    <div class="ml-3 text-sm">
                        <label for="email_new_rental" class="font-medium text-gray-700">New Rental Notification</label>
                        <p class="text-gray-500">Send email to administrators when a new rental is created.</p>
                    </div>
                </div>
                
                <!-- Rental Completed Notification -->
                <div class="flex items-start">
                    <div class="flex items-center h-5">
                        <input id="email_rental_completed" name="email_rental_completed" type="checkbox"
                            {{ \App\Models\Setting::getValue('notification.email_rental_completed', true) ? 'checked' : '' }}
                            class="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                    </div>
                    <div class="ml-3 text-sm">
                        <label for="email_rental_completed" class="font-medium text-gray-700">Rental Completed Notification</label>
                        <p class="text-gray-500">Send email when a rental is marked as completed.</p>
                    </div>
                </div>
                
                <!-- Payment Received Notification -->
                <div class="flex items-start">
                    <div class="flex items-center h-5">
                        <input id="email_payment_received" name="email_payment_received" type="checkbox"
                            {{ \App\Models\Setting::getValue('notification.email_payment_received', true) ? 'checked' : '' }}
                            class="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                    </div>
                    <div class="ml-3 text-sm">
                        <label for="email_payment_received" class="font-medium text-gray-700">Payment Received Notification</label>
                        <p class="text-gray-500">Send email confirmation when a payment is processed.</p>
                    </div>
                </div>
                
                <!-- Maintenance Due Notification -->
                <div class="flex items-start">
                    <div class="flex items-center h-5">
                        <input id="email_maintenance_due" name="email_maintenance_due" type="checkbox"
                            {{ \App\Models\Setting::getValue('notification.email_maintenance_due', true) ? 'checked' : '' }}
                            class="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                    </div>
                    <div class="ml-3 text-sm">
                        <label for="email_maintenance_due" class="font-medium text-gray-700">Maintenance Due Notification</label>
                        <p class="text-gray-500">Send email reminders for upcoming scheduled maintenance.</p>
                    </div>
                </div>
                
                <!-- Rental Reminder -->
                <div class="flex items-start">
                    <div class="flex items-center h-5">
                        <input id="email_rental_reminder" name="email_rental_reminder" type="checkbox"
                            {{ \App\Models\Setting::getValue('notification.email_rental_reminder', true) ? 'checked' : '' }}
                            class="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                    </div>
                    <div class="ml-3 text-sm">
                        <label for="email_rental_reminder" class="font-medium text-gray-700">Rental Reminder</label>
                        <p class="text-gray-500">Send email reminders to customers about upcoming rentals.</p>
                    </div>
                </div>
                
                <!-- Rental Expiring -->
                <div class="flex items-start">
                    <div class="flex items-center h-5">
                        <input id="email_rental_expiring" name="email_rental_expiring" type="checkbox"
                            {{ \App\Models\Setting::getValue('notification.email_rental_expiring', true) ? 'checked' : '' }}
                            class="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                    </div>
                    <div class="ml-3 text-sm">
                        <label for="email_rental_expiring" class="font-medium text-gray-700">Rental Expiring Notification</label>
                        <p class="text-gray-500">Alert customers when their rental period is about to end.</p>
                    </div>
                </div>
            </div>
            
            <hr class="my-8">
            
            <h2 class="text-lg font-medium text-gray-900 mb-4">System Notifications</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">
                <!-- Low Inventory Alert -->
                <div class="flex items-start">
                    <div class="flex items-center h-5">
                        <input id="system_low_inventory" name="system_low_inventory" type="checkbox"
                            {{ \App\Models\Setting::getValue('notification.system_low_inventory', true) ? 'checked' : '' }}
                            class="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                    </div>
                    <div class="ml-3 text-sm">
                        <label for="system_low_inventory" class="font-medium text-gray-700">Low Inventory Alert</label>
                        <p class="text-gray-500">Show dashboard alerts when PC components inventory is running low.</p>
                    </div>
                </div>
                
                <!-- Maintenance Notifications -->
                <div class="flex items-start">
                    <div class="flex items-center h-5">
                        <input id="system_maintenance_notifications" name="system_maintenance_notifications" type="checkbox"
                            {{ \App\Models\Setting::getValue('notification.system_maintenance_notifications', true) ? 'checked' : '' }}
                            class="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                    </div>
                    <div class="ml-3 text-sm">
                        <label for="system_maintenance_notifications" class="font-medium text-gray-700">Maintenance Notifications</label>
                        <p class="text-gray-500">Show alerts for scheduled and overdue maintenance tasks.</p>
                    </div>
                </div>
                
                <!-- Reservation Conflicts -->
                <div class="flex items-start">
                    <div class="flex items-center h-5">
                        <input id="system_reservation_conflicts" name="system_reservation_conflicts" type="checkbox"
                            {{ \App\Models\Setting::getValue('notification.system_reservation_conflicts', true) ? 'checked' : '' }}
                            class="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                    </div>
                    <div class="ml-3 text-sm">
                        <label for="system_reservation_conflicts" class="font-medium text-gray-700">Reservation Conflicts</label>
                        <p class="text-gray-500">Alert admin when there are scheduling conflicts for PC rentals.</p>
                    </div>
                </div>
                
                <!-- Daily Summary -->
                <div class="flex items-start">
                    <div class="flex items-center h-5">
                        <input id="system_daily_summary" name="system_daily_summary" type="checkbox"
                            {{ \App\Models\Setting::getValue('notification.system_daily_summary', false) ? 'checked' : '' }}
                            class="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                    </div>
                    <div class="ml-3 text-sm">
                        <label for="system_daily_summary" class="font-medium text-gray-700">Daily Summary</label>
                        <p class="text-gray-500">Receive a daily summary of rentals, payments, and maintenance tasks.</p>
                    </div>
                </div>
            </div>
            
            <hr class="my-8">
            
            <h2 class="text-lg font-medium text-gray-900 mb-4">Notification Recipients</h2>
            
            <div class="space-y-4">
                <!-- Admin Email for Notifications -->
                <div>
                    <label for="admin_notification_email" class="block text-sm font-medium text-gray-700 mb-1">Admin Notification Email</label>
                    <input type="email" id="admin_notification_email" name="admin_notification_email" 
                        value="{{ \App\Models\Setting::getValue('notification.admin_notification_email', '') }}"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                        placeholder="admin@example.com">
                    <p class="mt-1 text-xs text-gray-500">
                        Primary email address to receive administrative notifications.
                    </p>
                </div>
                
                <!-- Notification Frequency -->
                <div>
                    <label for="notification_frequency" class="block text-sm font-medium text-gray-700 mb-1">Notification Frequency</label>
                    <select id="notification_frequency" name="notification_frequency" 
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="immediately" {{ \App\Models\Setting::getValue('notification.notification_frequency', 'immediately') === 'immediately' ? 'selected' : '' }}>Immediately</option>
                        <option value="hourly" {{ \App\Models\Setting::getValue('notification.notification_frequency', 'immediately') === 'hourly' ? 'selected' : '' }}>Hourly Digest</option>
                        <option value="daily" {{ \App\Models\Setting::getValue('notification.notification_frequency', 'immediately') === 'daily' ? 'selected' : '' }}>Daily Digest</option>
                    </select>
                    <p class="mt-1 text-xs text-gray-500">
                        How often to send notification emails. Digest options will group multiple notifications together.
                    </p>
                </div>
            </div>
        </div>
        
        <div class="bg-gray-50 px-6 py-4 border-t border-gray-200 flex justify-between">
            <a href="{{ route('admin.settings.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-gray-700 hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back
            </a>
            
            <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                Save Notification Settings
            </button>
        </div>
    </form>
@endsection
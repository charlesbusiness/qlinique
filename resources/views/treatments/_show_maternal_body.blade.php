@php
    $priorCount = \App\Models\MaternalHealthRecord::where('patient_id', $record->patient_id)
        ->where('id', '<', $record->id)->count();
    $visitType = $record->created_by
        ? ($priorCount === 0 ? 'first_contact' : 'revisit')
        : 'unscheduled';
@endphp
@livewire('modals.manage-schedule-modal', [
    'recordId' => $record->id,
    'patientId' => $record->patient_id,
    'visitType' => $visitType,
    'isInline' => true,
], key('inline-schedule-' . $record->id))
@include('treatments._show_maternal_history', ['record' => $record, 'treatment' => $treatment])
@include('treatments._show_maternal_exams', ['record' => $record, 'treatment' => $treatment])

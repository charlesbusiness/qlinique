<?php

namespace App\Livewire\TreatmentComponents\MaternalHealth;

trait WithMaternalPregnancyHistory
{
    // Step 2: Pregnancy dating + Obstetric history
    public ?string $lmp = '';

    public string $cycle_regularity = '';

    public ?string $edd = '';

    public ?int $cga_weeks = null;

    public ?int $cga_days = null;

    public array $current_symptoms = [];

    public string $medications_exposures = '';

    public string $gravida = '';

    public string $term = '';

    public string $preterm = '';

    public string $abortion = '';

    public string $living = '';

    public array $prior_pregnancies = [];

    public string $prior_csection = '';

    public string $prior_csection_details = '';

    // Step 3: Medical + Family + Social history
    public array $chronic_conditions = [];

    public string $chronic_conditions_details = '';

    public array $infectious_disease_history = [];

    public string $prior_surgeries = '';

    public string $allergies = '';

    public string $current_medications = '';

    public array $family_genetic_history = [];

    public string $family_history_notes = '';

    public string $tobacco_vape = '';

    public ?int $tobacco_packs_per_day = null;

    public string $alcohol = '';

    public ?int $alcohol_drinks_per_week = null;

    public string $recreational_drugs = '';

    public string $recreational_drugs_details = '';

    public string $support_system = '';

    public string $safety_screening = '';

    public string $financial_stability = '';

    public string $intimate_partner_violence = '';

    public string $ipv_details = '';

    public string $occupation_hazard = '';

    public string $travel_history = '';

    public array $diet_intake = [];

    public array $physical_activities = [];
}

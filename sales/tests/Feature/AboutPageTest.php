<?php

test('the about page presents the organization and system purpose', function (): void {
    $this->get(route('about.index'))
        ->assertOk()
        ->assertSee('About Us')
        ->assertSee('Company Name')
        ->assertSee('Sales and Customer Management System')
        ->assertSee('Our Mission')
        ->assertSee('Our Vision')
        ->assertSee('What the System Connects')
        ->assertSee('Version 1.0');
});

test('the shared navigation links to about us from the utility section', function (): void {
    $this->get(route('dashboard'))
        ->assertOk()
        ->assertSee('About Us')
        ->assertSee(route('about.index'), false);
});

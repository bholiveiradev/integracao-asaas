<?php

it('returns a sucessful response', function() {
    $response = $this->get('/');

    expect($response)->assertStatus(200);
});

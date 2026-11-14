<?php 
    enum result_status : string {
        case REGISTERED = 'Registered';
        case DISQUALIFIED = 'Disqualified';
        case FINISHED = 'Finished';
        case NOT_FINISHED = 'Not finished';
    }

    enum age_category : string {
        case JUNIOR = 'Junior (under 18)';
        case INTERMEDIATE = 'Intermediate (18-24)';
        case SENIOR = 'Senior (25+)';
    }
?>
<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGetPatientProcedure extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $procedure = "DROP PROCEDURE IF EXISTS `getPatient`;";
        DB::unprepared($procedure);
        $procedure = "
        CREATE PROCEDURE `getPatient`(In idx INT)
        BEGIN
        SELECT patients.*,patientUser.email AS patientEmail,patientUser.profilePhoto AS patientProfilePhoto,patientGender.name AS patientGender,patientLanguage.name AS patientLanguage,patientOtherLanguage.name patientOtherLanguage,patientState.name AS patientState,patientCountry.name AS patientCountry ,
        patientFamilyMembers.id AS familyId,patientFamilyMembers.udid AS familyUdid,familyGender.name AS familyGender,familyRelation.name AS familyRelation,
patientFamilyMembers.fullName AS familyFullName,patientFamilyMembers.phoneNumber AS familyPhoneNumber,patientFamilyMembers.createdAt AS familyCreatedAt,
patientFamilyMembers.updatedAt AS familyUpdatedAt,patientFamilyMembers.deletedAt AS familyDeletedAt,patientFamilyMembers.isActive AS familyIsActive,
patientFamilyMembers.isDelete AS familyIsDelete,
patientFamilyMembers.contactTypeId AS familyContactType,
patientEmergencyContacts.id AS emeregencyId,
patientEmergencyContacts.udid AS emergencyUdid,patientEmergencyContacts.fullName AS emergencyFullName,emergencyGender.name AS emergencyGender,
patientEmergencyContacts.phoneNumber AS emergencyPhoneNumber,patientEmergencyContacts.patientId AS emergencyPatientId,
patientEmergencyContacts.sameAsFamily, 
patientVitals.id AS patientVitalId,patientVitals.udid AS patientVitalUdid,patientVitals.units,patientVitals.takeTime,patientVitals.startTime,
patientVitals.endTime,patientVitals.addType,patientVitals.comment,patientVitals.createdType,patientVitals.deviceInfo,
patientVitals.patientId AS patientVitalPatientId,patientVitals.value ,
patientFlag.id AS patientFlagId,patientFlag.udid AS patientFlagUdid,
flags.id AS flagId,flags.udid AS flagUdid,flags.name AS flagName,flags.color AS flagColor
FROM patients
LEFT JOIN users AS patientUser
ON patients.userId=patientUser.id
LEFT JOIN globalCodes AS patientGender
ON patients.genderId=patientGender.id
LEFT JOIN globalCodes AS patientLanguage
ON patients.languageId=patientLanguage.id
LEFT JOIN globalCodes AS patientState
ON patients.stateId=patientState.id
LEFT JOIN globalCodes AS patientCountry
ON patients.countryId=patientCountry.id
LEFT JOIN globalCodes AS patientOtherLanguage
ON patients.otherLanguageId=patientOtherLanguage.id
LEFT JOIN patientFlags
ON patients.id=patientFlags.patientId
LEFT JOIN patientVitals
ON patients.id=patientVitals.patientId
LEFT JOIN patientFamilyMembers
ON patients.id=patientFamilyMembers.patientId 
LEFT JOIN users AS familyUser
ON patientFamilyMembers.userId=familyUser.id
LEFT JOIN globalCodes AS familyGender
ON patientFamilyMembers.genderId=familyGender.id
LEFT JOIN globalCodes AS familyRelation
ON patientFamilyMembers.relationId=familyRelation.id
LEFT JOIN patientEmergencyContacts
ON patients.id=patientEmergencyContacts.patientId
LEFT JOIN globalCodes AS emergencyGender
ON patientEmergencyContacts.genderId=emergencyGender.id
LEFT JOIN patientFlags AS patientFlag
ON patients.id=patientFlag.patientId
LEFT JOIN flags
ON patientFlags.flagId=flags.id
WHERE patients.id=idx OR idx='';
END;";
        DB::unprepared($procedure);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('get_patient_procedure');
    }
}

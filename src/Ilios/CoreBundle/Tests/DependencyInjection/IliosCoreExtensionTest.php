<?php

namespace Ilios\CoreBundle\Tests\DependencyInjection;

use Matthias\SymfonyDependencyInjectionTest\PhpUnit\AbstractExtensionTestCase;
use Ilios\CoreBundle\DependencyInjection\IliosCoreExtension;

class IliosCoreExtensionTest extends AbstractExtensionTestCase
{

    protected function getContainerExtensions()
    {
        return array(
            new IliosCoreExtension()
        );
    }

    public function testParametersSet()
    {
        $fileSystemStoragePath = '/tmp/test';
        $ldapUrl = 'ldap.url';
        $ldapUser = 'ldap.user';
        $ldapPassword = 'ldap.pass';
        $ldapSearchBase = 'ldap.base';
        $ldapCampusIdProperty = 'ldap.camp';
        $ldapUsernameProperty = 'ldap.username';
        $this->load(array(
            'file_system_storage_path' => $fileSystemStoragePath,
            'ldap_directory_url' => $ldapUrl,
            'ldap_directory_user' => $ldapUser,
            'ldap_directory_password' => $ldapPassword,
            'ldap_directory_search_base' => $ldapSearchBase,
            'ldap_directory_campus_id_property' => $ldapCampusIdProperty,
            'ldap_directory_username_property' => $ldapUsernameProperty,
            'institution_domain' => 'ucsf.edu',
            'supporting_link' => 'https://inventory.ucsf.edu',
            'timezone' => 'America\Los_Angeles',
        ));
        $parameters = array(
            'ilioscore.form.handler.class' => 'Ilios\CoreBundle\Form\Handler',
            'ilioscore.basemanager.class' => 'Ilios\CoreBundle\Entity\Manager\BaseManager',
            'ilioscore.dtomanager.class' => 'Ilios\CoreBundle\Entity\Manager\DTOManager',
            'ilioscore.auditlog.manager.class' => 'Ilios\CoreBundle\Entity\Manager\AuditLogManager',
            'ilioscore.authentication.manager.class' => 'Ilios\CoreBundle\Entity\Manager\AuthenticationManager',
            'ilioscore.courselearningmaterial.manager.class' =>
                'Ilios\CoreBundle\Entity\Manager\CourseLearningMaterialManager',
            'ilioscore.course.manager.class' => 'Ilios\CoreBundle\Entity\Manager\CourseManager',
            'ilioscore.curriculuminventoryreport.manager.class' =>
                'Ilios\CoreBundle\Entity\Manager\CurriculumInventoryReportManager',
            'ilioscore.learningmaterial.manager.class' => 'Ilios\CoreBundle\Entity\Manager\LearningMaterialManager',
            'ilioscore.meshdescriptor.manager.class' => 'Ilios\CoreBundle\Entity\Manager\MeshDescriptorManager',
            'ilioscore.objective.manager.class' => 'Ilios\CoreBundle\Entity\Manager\ObjectiveManager',
            'ilioscore.offering.manager.class' => 'Ilios\CoreBundle\Entity\Manager\OfferingManager',
            'ilioscore.permission.manager.class' => 'Ilios\CoreBundle\Entity\Manager\PermissionManager',
            'ilioscore.pendinguserupdate.manager.class' => 'Ilios\CoreBundle\Entity\Manager\PendingUserUpdateManager',
            'ilioscore.programyearsteward.manager.class' => 'Ilios\CoreBundle\Entity\Manager\ProgramYearStewardManager',
            'ilioscore.school.manager.class' => 'Ilios\CoreBundle\Entity\Manager\SchoolManager',
            'ilioscore.sessiondescription.manager.class' => 'Ilios\CoreBundle\Entity\Manager\SessionDescriptionManager',
            'ilioscore.sessionlearningmaterial.manager.class' =>
                'Ilios\CoreBundle\Entity\Manager\SessionLearningMaterialManager',
            'ilioscore.user.manager.class' => 'Ilios\CoreBundle\Entity\Manager\UserManager',
            'ilioscore.dataloader.aamcmethod.class' => 'Ilios\CoreBundle\Tests\DataLoader\AamcMethodData',
            'ilioscore.dataloader.aamcpcrs.class' => 'Ilios\CoreBundle\Tests\DataLoader\AamcPcrsData',
            'ilioscore.dataloader.alertchangetype.class' => 'Ilios\CoreBundle\Tests\DataLoader\AlertChangeTypeData',
            'ilioscore.dataloader.alert.class' => 'Ilios\CoreBundle\Tests\DataLoader\AlertData',
            'ilioscore.dataloader.assessmentoption.class' => 'Ilios\CoreBundle\Tests\DataLoader\AssessmentOptionData',
            'ilioscore.dataloader.cohort.class' => 'Ilios\CoreBundle\Tests\DataLoader\CohortData',
            'ilioscore.dataloader.competency.class' => 'Ilios\CoreBundle\Tests\DataLoader\CompetencyData',
            'ilioscore.dataloader.courselearningmaterial.class' =>
                'Ilios\CoreBundle\Tests\DataLoader\CourseLearningMaterialData',
            'ilioscore.dataloader.course.class' => 'Ilios\CoreBundle\Tests\DataLoader\CourseData',
            'ilioscore.dataloader.courseclerkshiptype.class' =>
                'Ilios\CoreBundle\Tests\DataLoader\CourseClerkshipTypeData',
            'ilioscore.dataloader.curriculuminventoryacademiclevel.class' =>
                'Ilios\CoreBundle\Tests\DataLoader\CurriculumInventoryAcademicLevelData',
            'ilioscore.dataloader.curriculuminventoryexport.class' =>
                'Ilios\CoreBundle\Tests\DataLoader\CurriculumInventoryExportData',
            'ilioscore.dataloader.curriculuminventoryinstitution.class' =>
                'Ilios\CoreBundle\Tests\DataLoader\CurriculumInventoryInstitutionData',
            'ilioscore.dataloader.curriculuminventoryreport.class' =>
                'Ilios\CoreBundle\Tests\DataLoader\CurriculumInventoryReportData',
            'ilioscore.dataloader.curriculuminventorysequenceblock.class' =>
                'Ilios\CoreBundle\Tests\DataLoader\CurriculumInventorySequenceBlockData',
            'ilioscore.dataloader.curriculuminventorysequence.class' =>
                'Ilios\CoreBundle\Tests\DataLoader\CurriculumInventorySequenceData',
            'ilioscore.dataloader.department.class' => 'Ilios\CoreBundle\Tests\DataLoader\DepartmentData',
            'ilioscore.dataloader.ilmsession.class' => 'Ilios\CoreBundle\Tests\DataLoader\IlmSessionData',
            'ilioscore.dataloader.ingestionexception.class' =>
                'Ilios\CoreBundle\Tests\DataLoader\IngestionExceptionData',
            'ilioscore.dataloader.instructorgroup.class' => 'Ilios\CoreBundle\Tests\DataLoader\InstructorGroupData',
            'ilioscore.dataloader.learnergroup.class' => 'Ilios\CoreBundle\Tests\DataLoader\LearnerGroupData',
            'ilioscore.dataloader.learningmaterialstatus.class' =>
                'Ilios\CoreBundle\Tests\DataLoader\LearningMaterialStatusData',
            'ilioscore.dataloader.learningmaterialuserrole.class' =>
                'Ilios\CoreBundle\Tests\DataLoader\LearningMaterialUserRoleData',
            'ilioscore.dataloader.learningmaterial.class' =>
                'Ilios\CoreBundle\Tests\DataLoader\LearningMaterialData',
            'ilioscore.dataloader.objective.class' => 'Ilios\CoreBundle\Tests\DataLoader\ObjectiveData',
            'ilioscore.dataloader.offering.class' => 'Ilios\CoreBundle\Tests\DataLoader\OfferingData',
            'ilioscore.dataloader.programyear.class' => 'Ilios\CoreBundle\Tests\DataLoader\ProgramYearData',
            'ilioscore.dataloader.programyearsteward.class' =>
                'Ilios\CoreBundle\Tests\DataLoader\ProgramYearStewardData',
            'ilioscore.dataloader.program.class' => 'Ilios\CoreBundle\Tests\DataLoader\ProgramData',
            'ilioscore.dataloader.report.class' => 'Ilios\CoreBundle\Tests\DataLoader\ReportData',
            'ilioscore.dataloader.school.class' => 'Ilios\CoreBundle\Tests\DataLoader\SchoolData',
            'ilioscore.dataloader.sessiondescription.class' =>
                'Ilios\CoreBundle\Tests\DataLoader\SessionDescriptionData',
            'ilioscore.dataloader.sessionlearningmaterial.class' =>
                'Ilios\CoreBundle\Tests\DataLoader\SessionLearningMaterialData',
            'ilioscore.dataloader.sessiontype.class' => 'Ilios\CoreBundle\Tests\DataLoader\SessionTypeData',
            'ilioscore.dataloader.session.class' =>
                'Ilios\CoreBundle\Tests\DataLoader\SessionData',
            'ilioscore.dataloader.term.class' => 'Ilios\CoreBundle\Tests\DataLoader\TermData',
            'ilioscore.dataloader.usermadereminder.class' => 'Ilios\CoreBundle\Tests\DataLoader\UserMadeReminderData',
            'ilioscore.dataloader.userrole.class' => 'Ilios\CoreBundle\Tests\DataLoader\UserRoleData',
            'ilioscore.dataloader.user.class' => 'Ilios\CoreBundle\Tests\DataLoader\UserData',
            'ilioscore.dataloader.vocabulary.class' => 'Ilios\CoreBundle\Tests\DataLoader\VocabularyData',
            'ilios_core.ldap.url' => $ldapUrl,
            'ilios_core.ldap.user' => $ldapUser,
            'ilios_core.ldap.password' => $ldapPassword,
            'ilios_core.ldap.search_base' => $ldapSearchBase,
            'ilios_core.ldap.campus_id_property' => $ldapCampusIdProperty,
            'ilios_core.ldap.username_property' => $ldapUsernameProperty,
            'ilios_core.file_store_path' => $fileSystemStoragePath,
            'ilios_core.institution_domain' => 'ucsf.edu',
            'ilios_core.supporting_link' => 'https://inventory.ucsf.edu'
        );
        foreach ($parameters as $name => $value) {
            $this->assertContainerBuilderHasParameter($name, $value);
        }
        $services = array(
            'ilioscore.aamcmethod.handler',
            'ilioscore.aamcmethod.manager',
            'ilioscore.aamcpcrs.handler',
            'ilioscore.aamcpcrs.manager',
            'ilioscore.alert.handler',
            'ilioscore.alert.manager',
            'ilioscore.alertchangetype.handler',
            'ilioscore.alertchangetype.manager',
            'ilioscore.assessmentoption.handler',
            'ilioscore.assessmentoption.manager',
            'ilioscore.cohort.handler',
            'ilioscore.cohort.manager',
            'ilioscore.competency.handler',
            'ilioscore.competency.manager',
            'ilioscore.course.handler',
            'ilioscore.course.manager',
            'ilioscore.courseclerkshiptype.handler',
            'ilioscore.courseclerkshiptype.manager',
            'ilioscore.courselearningmaterial.handler',
            'ilioscore.courselearningmaterial.manager',
            'ilioscore.curriculuminventoryacademiclevel.handler',
            'ilioscore.curriculuminventoryacademiclevel.manager',
            'ilioscore.curriculuminventoryexport.handler',
            'ilioscore.curriculuminventoryexport.manager',
            'ilioscore.curriculuminventoryinstitution.handler',
            'ilioscore.curriculuminventoryinstitution.manager',
            'ilioscore.curriculuminventoryreport.handler',
            'ilioscore.curriculuminventoryreport.manager',
            'ilioscore.curriculuminventorysequence.handler',
            'ilioscore.curriculuminventorysequence.manager',
            'ilioscore.curriculuminventorysequenceblock.handler',
            'ilioscore.curriculuminventorysequenceblock.manager',
            'ilioscore.department.handler',
            'ilioscore.department.manager',
            'ilioscore.ilmsession.handler',
            'ilioscore.ilmsession.manager',
            'ilioscore.ingestionexception.handler',
            'ilioscore.ingestionexception.manager',
            'ilioscore.instructorgroup.handler',
            'ilioscore.instructorgroup.manager',
            'ilioscore.learnergroup.handler',
            'ilioscore.learnergroup.manager',
            'ilioscore.learningmaterial.handler',
            'ilioscore.learningmaterialstatus.handler',
            'ilioscore.learningmaterialstatus.manager',
            'ilioscore.learningmaterialuserrole.handler',
            'ilioscore.learningmaterialuserrole.manager',
            'ilioscore.meshconcept.handler',
            'ilioscore.meshconcept.manager',
            'ilioscore.meshdescriptor.handler',
            'ilioscore.meshdescriptor.manager',
            'ilioscore.meshpreviousindexing.handler',
            'ilioscore.meshpreviousindexing.manager',
            'ilioscore.meshqualifier.handler',
            'ilioscore.meshqualifier.manager',
            'ilioscore.meshsemantictype.handler',
            'ilioscore.meshsemantictype.manager',
            'ilioscore.meshterm.handler',
            'ilioscore.meshterm.manager',
            'ilioscore.objective.handler',
            'ilioscore.objective.manager',
            'ilioscore.offering.handler',
            'ilioscore.offering.manager',
            'ilioscore.permission.handler',
            'ilioscore.permission.manager',
            'ilioscore.program.handler',
            'ilioscore.program.manager',
            'ilioscore.programyear.handler',
            'ilioscore.programyear.manager',
            'ilioscore.programyearsteward.handler',
            'ilioscore.programyearsteward.manager',
            'ilioscore.report.handler',
            'ilioscore.report.manager',
            'ilioscore.school.handler',
            'ilioscore.school.manager',
            'ilioscore.session.handler',
            'ilioscore.session.manager',
            'ilioscore.sessiondescription.handler',
            'ilioscore.sessiondescription.manager',
            'ilioscore.sessionlearningmaterial.handler',
            'ilioscore.sessionlearningmaterial.manager',
            'ilioscore.sessiontype.handler',
            'ilioscore.sessiontype.manager',
            'ilioscore.term.handler',
            'ilioscore.term.manager',
            'ilioscore.user.handler',
            'ilioscore.user.manager',
            'ilioscore.usermadereminder.handler',
            'ilioscore.usermadereminder.manager',
            'ilioscore.userrole.handler',
            'ilioscore.userrole.manager',
            'ilioscore.vocabulary.handler',
            'ilioscore.vocabulary.manager',
            'ilioscore.dataloader.aamcmethod',
            'ilioscore.dataloader.aamcpcrs',
            'ilioscore.dataloader.alertchangetype',
            'ilioscore.dataloader.alert',
            'ilioscore.dataloader.assessmentoption',
            'ilioscore.dataloader.cohort',
            'ilioscore.dataloader.competency',
            'ilioscore.dataloader.courselearningmaterial',
            'ilioscore.dataloader.course',
            'ilioscore.dataloader.courseclerkshiptype',
            'ilioscore.dataloader.curriculuminventoryacademiclevel',
            'ilioscore.dataloader.curriculuminventoryexport',
            'ilioscore.dataloader.curriculuminventoryinstitution',
            'ilioscore.dataloader.curriculuminventoryreport',
            'ilioscore.dataloader.curriculuminventorysequenceblock',
            'ilioscore.dataloader.curriculuminventorysequence',
            'ilioscore.dataloader.department',
            'ilioscore.dataloader.ilmsession',
            'ilioscore.dataloader.ingestionexception',
            'ilioscore.dataloader.instructorgroup',
            'ilioscore.dataloader.learnergroup',
            'ilioscore.dataloader.learningmaterialstatus',
            'ilioscore.dataloader.learningmaterialuserrole',
            'ilioscore.dataloader.learningmaterial',
            'ilioscore.dataloader.objective',
            'ilioscore.dataloader.offering',
            'ilioscore.dataloader.programyear',
            'ilioscore.dataloader.programyearsteward',
            'ilioscore.dataloader.program',
            'ilioscore.dataloader.report',
            'ilioscore.dataloader.school',
            'ilioscore.dataloader.sessiondescription',
            'ilioscore.dataloader.sessionlearningmaterial',
            'ilioscore.dataloader.sessiontype',
            'ilioscore.dataloader.session',
            'ilioscore.dataloader.term',
            'ilioscore.dataloader.usermadereminder',
            'ilioscore.dataloader.userrole',
            'ilioscore.dataloader.user',
            'ilioscore.dataloader.vocabulary',
            'ilioscore.listener.timestamp',
            'ilioscore.listener.updatesession',
            'ilioscore.temporary_filesystem',
            'ilioscore.filesystem',
            'ilioscore.logger',
            'ilioscore.ldapmanager',
            'ilioscore.directory',
        );
        foreach ($services as $service) {
            $this->assertContainerBuilderHasService($service);
        }
    }
}

<?php
// general command
require_once 'StartCommand.php';
require_once 'HelpCommand.php';
require_once 'ShowKeyboardCommand.php';

//user command
require_once 'user/GetMyDataCommand.php';
require_once 'user/UpdateMyEmailCommand.php';
require_once 'user/UpdateMyEmailConfirmCodeCommand.php';
require_once 'user/UpdateMyPublicAliasCommand.php';
require_once 'user/UpdateMyPasswordCommand.php';
require_once 'user/ChangeLanguageCommand.php';
require_once 'user/SuggestNewLanguageCommand.php';
require_once 'user/GetMyInvitationCodesCommand.php';
require_once 'user/MyInterestsAndValuesCommand.php';
require_once 'user/AddNewInterestsCommand.php';
require_once 'user/SetUserInterestsCommand.php';
require_once 'user/ClearAllInterestsQuestionCommand.php';
require_once 'user/ClearAllInterestsCommand.php';
require_once  'user/MyConnectionsCommand.php';
require_once  'user/AddNewConnectionCommand.php';
require_once  'user/DeleteConnectionsCommand.php';
require_once  'user/SentInvitesCommand.php';
require_once  'user/PendingInvitesCommand.php';
require_once  'user/RejectedInvitesCommand.php';
require_once  'user/MyLovestarsCommand.php';
require_once  'user/ResendPendingInvitesCommand.php';
require_once  'user/GenerateCodesCommand.php';
require_once  'user/ReportAnIssueCommand.php';
require_once  'user/MyMatchesCommand.php';
require_once  'user/ClaimMyLovestarsCommand.php';
require_once 'user/InterestsAnswersCommand.php';
require_once 'user/UploadAvatarCommand.php';
require_once 'user/DieCommand.php';

//registration user command
require_once 'registration/RegistrationStep1Command.php';
require_once 'registration/RegistrationStep2Command.php';
require_once 'registration/RegistrationStep3Command.php';
require_once 'registration/RegistrationStep4Command.php';
require_once 'registration/RegistrationStepInvitationCodeCommand.php';

//teacher command
//require_once 'teacher/TeacherCreateCommand.php';
//require_once 'teacher/TeacherCreateStep2Command.php';
//require_once 'teacher/TeacherCreateStep3Command.php';
//require_once 'teacher/TeacherCreateStep4Command.php';
//require_once 'teacher/TeacherUpdateCommand.php';
//require_once 'teacher/TeacherUpdateTitleCommand.php';
//require_once 'teacher/TeacherUpdatePublicAliasCommand.php';
//require_once 'teacher/TeacherUpdateDescriptionCommand.php';
//require_once 'teacher/TeacherUpdateHashtagsCommand.php';
//require_once 'teacher/TeacherArchiveCommand.php';
//require_once 'teacher/TeachersListCommand.php';
//require_once 'teacher/TeacherSetActiveCommand.php';
//require_once 'teacher/TeacherCheckActiveCommand.php';
//require_once 'teacher/AssignUserToActiveTeacherCommand.php';

//Events command
require_once 'events/EventsCreateCommand.php';
require_once 'events/GetMyEventsCommand.php';

//expressions commands
require_once 'expressions/ExpressionStartCreateCommand.php';
require_once 'expressions/ExpressionChooseTypeCommand.php';
require_once 'expressions/ExpressionChooseDescriptionCommand.php';
require_once 'expressions/ExpressionChooseTagsCommand.php';
require_once 'expressions/ExpressionChooseFileCommand.php';
require_once 'expressions/ExpressionConfirmCreationCommand.php';
require_once 'expressions/ExpressionCancelCreationCommand.php';
require_once 'expressions/ExpressionFinishedCreationCommand.php';
require_once 'expressions/ExploreCeCommand.php';
require_once 'expressions/ViewCreativeExpressions.php';
require_once 'expressions/ExpressionChooseExpirationCommand.php';
// general command
$telegram->addCommand(Telegram\Bot\Commands\StartCommand::class);
$telegram->addCommand( Telegram\Bot\Commands\HelpCommand::class);
$telegram->addCommand( Telegram\Bot\Commands\ShowKeyboardCommand::class);
//user command
$telegram->addCommand( Telegram\Bot\Commands\GetMyDataCommand::class);
//$telegram->addCommand( Telegram\Bot\Commands\UpdateMyEmailCommand::class);
//$telegram->addCommand( Telegram\Bot\Commands\UpdateMyEmailConfirmCodeCommand::class);
$telegram->addCommand( Telegram\Bot\Commands\UpdateMyPublicAliasCommand::class);
$telegram->addCommand( Telegram\Bot\Commands\UpdateMyPasswordCommand::class);
$telegram->addCommand( Telegram\Bot\Commands\ChangeLanguageCommand::class);
$telegram->addCommand( Telegram\Bot\Commands\SuggestNewLanguageCommand::class);
$telegram->addCommand( Telegram\Bot\Commands\GetMyInvitationCodesCommand::class);
$telegram->addCommand( Telegram\Bot\Commands\MyInterestsAndValuesCommand::class);
$telegram->addCommand( Telegram\Bot\Commands\AddNewInterestsCommand::class);
$telegram->addCommand( Telegram\Bot\Commands\SetUserInterestsCommand::class);
$telegram->addCommand( Telegram\Bot\Commands\ClearAllInterestsQuestionCommand::class);
$telegram->addCommand( Telegram\Bot\Commands\ClearAllInterestsCommand::class);
$telegram->addCommand( Telegram\Bot\Commands\MyConnectionsCommand::class);
$telegram->addCommand( Telegram\Bot\Commands\AddNewConnectionCommand::class);
$telegram->addCommand( Telegram\Bot\Commands\DeleteConnectionsCommand::class);
$telegram->addCommand( Telegram\Bot\Commands\SentInvitesCommand::class);
$telegram->addCommand( Telegram\Bot\Commands\PendingInvitesCommand::class);
$telegram->addCommand( Telegram\Bot\Commands\RejectedInvitesCommand::class);
$telegram->addCommand( Telegram\Bot\Commands\MyLovestarsCommand::class);
$telegram->addCommand( Telegram\Bot\Commands\ResendPendingInvitesCommand::class);
$telegram->addCommand( Telegram\Bot\Commands\ReportAnIssueCommand::class);
$telegram->addCommand(Telegram\Bot\Commands\MyMatchesCommand::class);
$telegram->addCommand(Telegram\Bot\Commands\ClaimMyLovestarsCommand::class);
$telegram->addCommand(Telegram\Bot\Commands\InterestsAnswersCommand::class);
$telegram->addCommand(Telegram\Bot\Commands\UploadAvatarCommand::class);


//registration user command
$telegram->addCommand( Telegram\Bot\Commands\RegistrationStep1Command::class);
$telegram->addCommand( Telegram\Bot\Commands\RegistrationStep2Command::class);
$telegram->addCommand( Telegram\Bot\Commands\RegistrationStep3Command::class);
$telegram->addCommand( Telegram\Bot\Commands\RegistrationStep4Command::class);
$telegram->addCommand( Telegram\Bot\Commands\RegistrationStepInvitationCodeCommand::class);
//expressions commands
$telegram->addCommand( Telegram\Bot\Commands\ExpressionStartCreateCommand::class);
$telegram->addCommand( Telegram\Bot\Commands\ExpressionChooseTypeCommand::class);
$telegram->addCommand( Telegram\Bot\Commands\ExpressionChooseDescriptionCommand::class);
$telegram->addCommand( Telegram\Bot\Commands\ExpressionChooseTagsCommand::class);
$telegram->addCommand( Telegram\Bot\Commands\ExpressionChooseFileCommand::class);
$telegram->addCommand( Telegram\Bot\Commands\ExpressionConfirmCreationCommand::class);
$telegram->addCommand( Telegram\Bot\Commands\ExpressionCancelCreationCommand::class);
$telegram->addCommand( Telegram\Bot\Commands\ExpressionFinishedCreationCommand::class);
$telegram->addCommand( Telegram\Bot\Commands\ExploreCeCommand::class);
$telegram->addCommand( Telegram\Bot\Commands\ViewCreativeExpressionsCommand::class);
$telegram->addCommand( Telegram\Bot\Commands\ExpressionChooseExpirationCommand::class);


//teacher command
//$telegram->addCommand( Telegram\Bot\Commands\TeacherCreateCommand::class);
//$telegram->addCommand( Telegram\Bot\Commands\TeacherCreateStep2Command::class);
//$telegram->addCommand( Telegram\Bot\Commands\TeacherCreateStep3Command::class);
//$telegram->addCommand( Telegram\Bot\Commands\TeacherCreateStep4Command::class);
//$telegram->addCommand( Telegram\Bot\Commands\TeacherUpdateCommand::class);
//$telegram->addCommand( Telegram\Bot\Commands\TeacherUpdateTitleCommand::class);
//$telegram->addCommand( Telegram\Bot\Commands\TeacherUpdatePublicAliasCommand::class);
//$telegram->addCommand( Telegram\Bot\Commands\TeacherUpdateDescriptionCommand::class);
//$telegram->addCommand( Telegram\Bot\Commands\TeacherUpdateHashtagsCommand::class);
//$telegram->addCommand( Telegram\Bot\Commands\TeacherArchiveCommand::class);
//$telegram->addCommand( Telegram\Bot\Commands\TeachersListCommand::class);
//$telegram->addCommand( Telegram\Bot\Commands\TeacherSetActiveCommand::class);
//$telegram->addCommand( Telegram\Bot\Commands\TeacherCheckActiveCommand::class);
//$telegram->addCommand( Telegram\Bot\Commands\AssignUserToActiveTeacherCommand::class);


$update = $telegram->getWebhookUpdate();
$telegram_id = $update->getMessage()->chat->id;
$user = user_is_verified($telegram_id);

if(in_array($user['user']['role'], ['event_organizer', 'admin'])) {
    //Events command
    $telegram->addCommand( Telegram\Bot\Commands\EventsCreateCommand::class);
    $telegram->addCommand( Telegram\Bot\Commands\GetMyEventsCommand::class);
}

if($user['user']['role'] === 'admin') {
    $telegram->addCommand( Telegram\Bot\Commands\GenerateCodesCommand::class);
    $telegram->addCommand( Telegram\Bot\Commands\DieCommand::class);
}
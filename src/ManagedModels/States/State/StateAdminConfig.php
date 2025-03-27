<?php

namespace Thinktomorrow\Chief\ManagedModels\States\State;

interface StateAdminConfig extends StateConfig
{
    /**
     * The title of the window tile on a page.
     */
    public function getEditTitle(StatefulContract $statefulContract): string;

    /**
     * The optional intro content on the edit view of the state. This can
     * be used to display an inline message to the user.
     */
    public function getEditContent(StatefulContract $statefulContract): ?string;

    /**
     * Transition button label
     */
    public function getTransitionLabel(StatefulContract $statefulContract, string $transitionKey): ?string;

    /**
     * This indicates in a visual manner the type of transition. Options are:
     * success, info, warning, error, grey
     */
    public function getTransitionType(StatefulContract $statefulContract, string $transitionKey): ?string;

    /**
     * Optional callout title
     */
    public function getTransitionTitle(StatefulContract $statefulContract, string $transitionKey): ?string;

    /**
     * Optional callout message to show next to the transition button. If a confirmation modal
     * is used, this message will be shown in the modal instead of on the edit view
     */
    public function getTransitionContent(StatefulContract $statefulContract, string $transitionKey): ?string;

    /**
     * Indicates whether or not this transition action requires confirmation via modal
     * Might be a good idea for destructive actions.
     */
    public function hasConfirmationForTransition(string $transitionKey): bool;

    public function getConfirmationLabel(StatefulContract $statefulContract, string $transitionKey): ?string;

    public function getConfirmationTitle(StatefulContract $statefulContract, string $transitionKey): ?string;

    public function getConfirmationContent(StatefulContract $statefulContract, string $transitionKey): ?string;

    /**
     * Optional message to show next to the transition button. If a confirmation modal
     * is used, this message will be shown in the modal instead of on the edit view
     */
    public function getConfirmationFields(StatefulContract $statefulContract, string $transitionKey): iterable;

    /**
     * Should the request be redirected after this transition and to where.
     */
    public function getRedirectAfterTransition(StatefulContract $statefulContract, string $transitionKey): ?string;

    /**
     * The notification shown in the notification bubble
     */
    public function getResponseNotification(string $transitionKey): ?string;
}

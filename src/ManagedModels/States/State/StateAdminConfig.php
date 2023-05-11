<?php

namespace Thinktomorrow\Chief\ManagedModels\States\State;

interface StateAdminConfig extends StateConfig
{
    /**
     * The title of the window tile on a page.
     */
    public function getWindowTitle(StatefulContract $statefulContract): string;

    /**
     * The content of the window tile on a page. The second parameter is the
     * defined view data of the page containing the window.
     */
    public function getWindowContent(StatefulContract $statefulContract, array $viewData): string;

    /**
     * The html representing a state. This is shown as label
     * on the index and as label in the window tile
     */
    public function getStateLabel(StatefulContract $statefulContract): ?string;

    /**
     * The optional intro content on the edit view of the state. This can
     * be used to display an inline message to the user.
     */
    public function getEditContent(StatefulContract $statefulContract): ?string;

    /**
     * Transition button label
     */
    public function getTransitionButtonLabel(string $transitionKey): ?string;

    /**
     * This indicates in a visual manner the type of transition. Options are:
     * success, info, warning, error, grey
     */
    public function getTransitionType(string $transitionKey): ?string;

    /**
     * Optional message to show next to the transition button. If a confirmation modal
     * is used, this message will be shown in the modal instead of on the edit view
     */
    public function getTransitionContent(string $transitionKey): ?string;

    /**
     * Optional message to show next to the transition button. If a confirmation modal
     * is used, this message will be shown in the modal instead of on the edit view
     */
    public function getTransitionFields(string $transitionKey, StatefulContract $statefulContract): iterable;

    /**
     * Indicates whether or not this transition action requires confirmation via modal
     * Might be a good idea for destructive actions.
     */
    public function hasConfirmationForTransition(string $transitionKey): bool;

    /**
     * For modals with heavy content, you can consider loading the modal content async.
     * Here you can provide the url to request the async content from.
     */
    public function getAsyncModalUrl(string $transitionKey, StatefulContract $statefulContract): ?string;

    /**
     * Should the request be redirected after this transition and to where.
     */
    public function getRedirectAfterTransition(string $transitionKey, StatefulContract $statefulContract): ?string;

    /**
     * The notification shown in the notification bubble
     *
     * @param string $transitionKey
     * @return string|null
     */
    public function getResponseNotification(string $transitionKey): ?string;
}

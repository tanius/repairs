{*
Copyright 2011-2017 Nick Korbel

This file is part of Booked Scheduler.

Booked Scheduler is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

Booked Scheduler is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with Booked Scheduler.  If not, see <http://www.gnu.org/licenses/>.
*}
<div id="resourceDetailsPopup">
    <h4{if !empty($color)} style="background-color:{$color};color:{$textColor};padding:5px 3px;"{/if}>{$resourceName}</h4>
    <a href="#" class="visible-sm hideResourceDetailsPopup">{translate key=Close}</a>

    {if $imageUrl neq ''}
        <div class="resourceImage">
            <img src="{$imageUrl}" alt="{$resourceName|escape}"/>
        </div>
    {/if}
    <div class="description">
        <span class="bold">{translate key=ResourceDescription}</span>
        {if $description neq ''}
            {$description|html_entity_decode|url2link|nl2br}
        {else}
            {translate key=NoResourceDescriptionLabel}
        {/if}
        <br/>
        <span class="bold">{translate key=Notes}</span>
        {if $notes neq ''}
            {$notes|html_entity_decode|url2link|nl2br}
        {else}
            {translate key=NoNotesLabel}
        {/if}
        <br/>
        <span class="bold">{translate key=Contact}</span>
        {if $contactInformation neq ''}
            {$contactInformation}
        {else}
            {translate key=NoContactLabel}
        {/if}
        <br/>
        <span class="bold">{translate key=Location}</span>
        {if $locationInformation neq ''}
            {$locationInformation}
        {else}
            {translate key=NoLocationLabel}
        {/if}
        <br/>
        <span class="bold">{translate key=ResourceType}</span>
        {if $resourceType neq ''}
            {$resourceType}
        {else}
            {translate key=NoResourceTypeLabel}
        {/if}
        {if $Attributes|count > 0}
            {foreach from=$Attributes item=attribute}
                <div>
                    {control type="AttributeControl" attribute=$attribute readonly=true}
                </div>
            {/foreach}
        {/if}
        {if $ResourceTypeAttributes|count > 0}
            {foreach from=$ResourceTypeAttributes item=attribute}
                <div>
                    {control type="AttributeControl" attribute=$attribute readonly=true}
                </div>
            {/foreach}
        {/if}
    </div>
    <div class="attributes">
        <div>
            {if $minimumDuration neq ''}
                {translate key='ResourceMinLength' args=$minimumDuration}
            {else}
                {translate key='ResourceMinLengthNone'}
            {/if}
        </div>
        <div>
            {if $maximumDuration neq ''}
                {translate key='ResourceMaxLength' args=$maximumDuration}
            {else}
                {translate key='ResourceMaxLengthNone'}
            {/if}
        </div>
        <div>
            {if $requiresApproval}
                {translate key='ResourceRequiresApproval'}
            {else}
                {translate key='ResourceRequiresApprovalNone'}
            {/if}
        </div>
        <div>
            {if $minimumNotice neq ''}
                {translate key='ResourceMinNotice' args=$minimumNotice}
            {else}
                {translate key='ResourceMinNoticeNone'}
            {/if}
        </div>
        <div>
            {if $maximumNotice neq ''}
                {translate key='ResourceMaxNotice' args=$maximumNotice}
            {else}
                {translate key='ResourceMaxNoticeNone'}
            {/if}
        </div>
        <div>
            {if $allowMultiday}
                {translate key='ResourceAllowMultiDay'}
            {else}
                {translate key='ResourceNotAllowMultiDay'}
            {/if}
        </div>
        <div>
            {if $maxParticipants neq ''}
                {translate key='ResourceCapacity' args=$maxParticipants}
            {else}
                {translate key='ResourceCapacityNone'}
            {/if}
        </div>

        {if $autoReleaseMinutes neq ''}
            <div>
                {translate key='AutoReleaseNotification' args=$autoReleaseMinutes}
            </div>
        {/if}
        {if $isCheckInEnabled neq ''}
            <div>
                {translate key='RequiresCheckInNotification'}
            </div>
        {/if}

        {if $creditsEnabled}
            <div>
                {translate key=CreditUsagePerSlot args=$offPeakCredits}
            </div>
            <div>
                {translate key=PeakCreditUsagePerSlot args=$peakCredits}
            </div>
        {/if}
    </div>
    <div style="clearfix">&nbsp;</div>
</div>

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
{if $authorized}
    <div class="res_popup_details" style="margin:0">

        {capture "name"}
            <div class="user">
		{* Local change: Always show user names. *}
		{$fullName}
		{*
                {if $hideUserInfo || $hideDetails}
                    {translate key=Private}
                {else}
                    {$fullName}
                {/if}
		*}
            </div>
        {/capture}
        {$formatter->Add('name', $smarty.capture.name)}

        {capture "email"}
            <div class="email">
                {if !$hideUserInfo && !$hideDetails}
                    {$email}
                {/if}
            </div>
        {/capture}
        {$formatter->Add('email', $smarty.capture.email)}

        {capture "phone"}
            <div class="phone">
                {if !$hideUserInfo && !$hideDetails}
                    {$phone}
                {/if}
            </div>
        {/capture}
        {$formatter->Add('phone', $smarty.capture.phone)}

        {capture "dates"}
            {assign var="key" value="res_popup"}
            {if $startDate->DateEquals($endDate)}
                {assign var="key" value="res_popup_time"}
            {/if}
            <div class="dates">{formatdate date=$startDate key=res_popup} - {formatdate date=$endDate key=$key}</div>
        {/capture}
        {$formatter->Add('dates', $smarty.capture.dates)}

        {capture "title"}
            {if !$hideDetails}
                <div class="title">{if $title neq ''}{$title}{else}{translate key=NoTitleLabel}{/if}</div>
            {/if}
        {/capture}
        {$formatter->Add('title', $smarty.capture.title)}

        {capture "resources"}
            <div class="resources">
                {translate key="Resources"} ({$resources|@count}):
                {foreach from=$resources item=resource name=resource_loop}
                    {$resource->Name()}
                    {if !$smarty.foreach.resource_loop.last}, {/if}
                {/foreach}
            </div>
        {/capture}
        {$formatter->Add('resources', $smarty.capture.resources)}

        {capture "participants"}
            {if !$hideUserInfo && !$hideDetails}
                <div class="users">
                    {translate key="Participants"} ({$participants|@count}):
                    {foreach from=$participants item=user name=participant_loop}
                        {if !$user->IsOwner()}
                            {fullname first=$user->FirstName last=$user->LastName}
                        {/if}
                        {if !$smarty.foreach.participant_loop.last}, {/if}
                    {/foreach}
                </div>
            {/if}
        {/capture}
        {$formatter->Add('participants', $smarty.capture.participants)}

        {capture "accessories"}
            {if !$hideDetails}
                <div class="accessories">
                    {translate key="Accessories"} ({$accessories|@count}):
                    {foreach from=$accessories item=accessory name=accessory_loop}
                        {$accessory->Name} ({$accessory->QuantityReserved})
                        {if !$smarty.foreach.accessory_loop.last}, {/if}
                    {/foreach}
                </div>
            {/if}
        {/capture}
        {$formatter->Add('accessories', $smarty.capture.accessories)}

        {capture "description"}
            {if !$hideDetails}
                <div class="summary">{if $summary neq ''}{$summary|truncate:300:"..."|nl2br}{else}{translate key=NoDescriptionLabel}{/if}</div>
            {/if}
        {/capture}
        {$formatter->Add('description', $smarty.capture.description)}

        {capture "attributes"}
            {if !$hideDetails}
                {if $attributes|count > 0}
                    <br/>
                    {foreach from=$attributes item=attribute}
                        {assign var=attr value="att`$attribute->Id()`"}
                        {capture name="attr"}
                            <div>{control type="AttributeControl" attribute=$attribute readonly=true}</div>
                        {/capture}
                        {$smarty.capture.attr}
                        {$formatter->Add($attr, $smarty.capture.attr)}
                    {/foreach}
                {/if}
            {/if}
        {/capture}
        {$formatter->Add('attributes', $smarty.capture.attributes)}

        {capture "pending"}
            {if $requiresApproval}
                <div class="pendingApproval">{translate key=PendingApproval}</div>
            {/if}
        {/capture}
        {$formatter->Add('pending', $smarty.capture.pending)}

        {capture "duration"}
            <div class="duration">{$duration}</div>
        {/capture}
        {$formatter->Add('duration', $smarty.capture.duration)}
        <!-- {$ReservationId} -->

        {$formatter->Display()}
    </div>
{else}
    {translate key='InsufficientPermissionsError'}
{/if}

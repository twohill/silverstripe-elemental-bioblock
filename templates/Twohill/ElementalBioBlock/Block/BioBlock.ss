<%-- Display the image (File) --%>
<% if $File %>
    <span class="bio-element__image">
        $File
    </span>

<% end_if %>
<div class="bio-element__content">
    <% if $Title && $ShowTitle %>
        <h2 class="bio-element__title">$Title</h2>
    <% end_if %>

    <% if $Role %>
        <p class="bio-element__role">$Title</p>
    <% end_if %>

    $Content

    <%-- Add a CallToActionLink if available --%>
    <% if $CallToActionLink.Page.Link %>
        <div class="bio-element__call-to-action-container">
        <% with $CallToActionLink %>
            <a href="{$Page.Link}" class="bio-element__call-to-action"
                <% if $TargetBlank %>target="_blank"<% end_if %>
                <% if $Description %>title="{$Description.ATT}"<% end_if %>>
                {$Text.XML}
            </a>
        <% end_with %>
        </div>
    <% end_if %>
</div>

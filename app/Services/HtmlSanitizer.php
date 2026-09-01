<?php

namespace App\Services;

use DOMDocument;
use DOMElement;
use DOMNode;

class HtmlSanitizer
{
    /**
     * Whitelist of allowed HTML elements.
     *
     * @var array<string>
     */
    protected static array $allowedTags = [
        'p', 'br', 'strong', 'b', 'em', 'i', 'u', 's', 'strike',
        'ul', 'ol', 'li', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6',
        'blockquote', 'pre', 'code', 'span', 'hr',
        'table', 'thead', 'tbody', 'tr', 'th', 'td', 'a'
    ];

    /**
     * Whitelist of allowed attributes per element.
     *
     * @var array<string, array<string>>
     */
    protected static array $allowedAttributes = [
        'a' => ['href', 'title', 'target', 'rel'],
        'th' => ['colspan', 'rowspan', 'scope', 'class'],
        'td' => ['colspan', 'rowspan', 'class'],
        'span' => ['class'],
        'p' => ['class'],
        'div' => ['class'],
        'h1' => ['class'],
        'h2' => ['class'],
        'h3' => ['class'],
        'h4' => ['class'],
        'h5' => ['class'],
        'h6' => ['class'],
        'ul' => ['class'],
        'ol' => ['class'],
        'li' => ['class'],
        'blockquote' => ['class'],
        'code' => ['class'],
        'pre' => ['class'],
    ];

    /**
     * Clean and sanitize the given HTML string against XSS attacks.
     *
     * @param string|null $html
     * @return string
     */
    public static function clean(?string $html): string
    {
        if ($html === null || trim($html) === '') {
            return '';
        }

        // Quick check: if there are no HTML tags, return safe escaped/raw text
        if (!str_contains($html, '<')) {
            return $html;
        }

        $dom = new DOMDocument('1.0', 'UTF-8');
        
        // Suppress libxml errors for malformed HTML fragments
        $previousLibxmlErrorHandling = libxml_use_internal_errors(true);

        // Wrap fragment in UTF-8 HTML boilerplate to ensure correct encoding
        $wrapper = '<!DOCTYPE html><html><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8"></head><body>' 
                 . $html 
                 . '</body></html>';

        $dom->loadHTML($wrapper, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
        libxml_clear_errors();
        libxml_use_internal_errors($previousLibxmlErrorHandling);

        $body = $dom->getElementsByTagName('body')->item(0);
        if (!$body) {
            return '';
        }

        self::sanitizeNode($body);

        // Export sanitized inner body HTML
        $sanitizedHtml = '';
        foreach ($body->childNodes as $child) {
            $sanitizedHtml .= $dom->saveHTML($child);
        }

        return trim($sanitizedHtml);
    }

    /**
     * Recursively sanitize DOM nodes.
     *
     * @param DOMNode $node
     */
    protected static function sanitizeNode(DOMNode $node): void
    {
        $children = [];
        foreach ($node->childNodes as $child) {
            $children[] = $child;
        }

        foreach ($children as $child) {
            if ($child->nodeType === XML_ELEMENT_NODE) {
                /** @var DOMElement $child */
                $tagName = strtolower($child->tagName);

                // If tag is not allowed, remove the node entirely if dangerous, or unwrap if text
                if (!in_array($tagName, self::$allowedTags, true)) {
                    if (in_array($tagName, ['script', 'style', 'iframe', 'object', 'embed', 'applet', 'svg', 'math', 'form', 'input', 'button', 'select', 'textarea'], true)) {
                        $node->removeChild($child);
                        continue;
                    } else {
                        // For non-dangerous disallowed tags (e.g. unknown wrappers), unwrap content
                        while ($child->hasChildNodes()) {
                            $node->insertBefore($child->firstChild, $child);
                        }
                        $node->removeChild($child);
                        continue;
                    }
                }

                // Sanitize attributes
                if ($child->hasAttributes()) {
                    $attributesToRemove = [];
                    foreach ($child->attributes as $attr) {
                        $attrName = strtolower($attr->name);
                        $attrValue = $attr->value;

                        // Disallow any on* event handlers (onclick, onerror, onload, etc.)
                        if (str_starts_with($attrName, 'on')) {
                            $attributesToRemove[] = $attr->name;
                            continue;
                        }

                        // Check attribute allowlist
                        $tagAllowedAttrs = self::$allowedAttributes[$tagName] ?? ['class'];
                        if (!in_array($attrName, $tagAllowedAttrs, true)) {
                            $attributesToRemove[] = $attr->name;
                            continue;
                        }

                        // Specific validation for href attributes
                        if ($attrName === 'href') {
                            $trimmedValue = trim($attrValue);
                            // Only allow http://, https://, mailto:, or relative anchors (#)
                            if (!preg_match('/^(https?:\/\/|mailto:|\/|#)/i', $trimmedValue) || preg_match('/^(javascript|vbscript|data):/i', $trimmedValue)) {
                                $attributesToRemove[] = $attr->name;
                            }
                        }

                        // Class attribute validation: safe class names only
                        if ($attrName === 'class') {
                            $sanitizedClasses = preg_replace('/[^a-zA-Z0-9_\-\s]/', '', $attrValue);
                            $child->setAttribute('class', trim($sanitizedClasses));
                        }
                    }

                    foreach ($attributesToRemove as $attrNameToRemove) {
                        $child->removeAttribute($attrNameToRemove);
                    }

                    // Enforce safe attributes on <a> tags
                    if ($tagName === 'a') {
                        $child->setAttribute('rel', 'noopener noreferrer');
                        $child->setAttribute('target', '_blank');
                    }
                }

                // Recursively process child elements
                if ($child->hasChildNodes()) {
                    self::sanitizeNode($child);
                }
            }
        }
    }
}


/**
 * Quill Rich Text Editor Configuration
 * This module provides a global function to initialize Quill editor
 * for text formatting (bold, italic, links, lists) without image/video upload
 */

import Quill from 'quill';
import 'quill/dist/quill.snow.css';

// Make Quill available globally for Alpine.js and other scripts
window.Quill = Quill;

/**
 * Initialize Quill Editor
 * @param {string} selector - CSS selector for the editor container
 * @param {string} wireModel - Livewire model name to sync data
 * @returns {Quill} - Quill editor instance
 */
window.initQuillEditor = function(selector, wireModel) {
    const container = document.querySelector(selector);
    if (!container) {
        console.error(`Quill: Element with selector "${selector}" not found`);
        return null;
    }

    // Configure toolbar with text formatting only (no images/videos)
    const toolbarOptions = [
        ['bold', 'italic', 'underline', 'strike'],        // toggled buttons
        ['blockquote', 'code-block'],
        
        [{ 'header': 1 }, { 'header': 2 }],               // custom button values
        [{ 'list': 'ordered'}, { 'list': 'bullet' }],
        [{ 'script': 'sub'}, { 'script': 'super' }],      // superscript/subscript
        
        [{ 'header': [1, 2, 3, 4, 5, 6, false] }],
        
        [{ 'color': [] }, { 'background': [] }],          // dropdown with defaults
        [{ 'align': [] }],
        
        ['link'],                                          // link only, no image/video
        
        ['clean']                                          // remove formatting button
    ];

    // Initialize Quill
    const quill = new Quill(selector, {
        theme: 'snow',
        modules: {
            toolbar: toolbarOptions
        },
        placeholder: 'Enter your announcement message here...',
    });

    // Sync with Livewire
    quill.on('text-change', function() {
        const content = quill.root.innerHTML;
        // Update Livewire model
        if (window.Livewire) {
            window.Livewire.emit('update-quill-content', 'message', html);

        }
    });

    return quill;
};

/**
 * Update Quill content programmatically
 * @param {Quill} quill - Quill editor instance
 * @param {string} content - HTML content to set
 */
window.updateQuillContent = function(quill, content) {
    if (quill && content) {
        quill.root.innerHTML = content;
    }
};

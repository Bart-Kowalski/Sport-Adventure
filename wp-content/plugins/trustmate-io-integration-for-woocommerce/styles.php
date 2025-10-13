<?php

add_action('admin_head', 'trustmate_styles');

function trustmate_styles() {
    echo '
        <style>
            .tm-install {
                max-width: 768px;
                border: solid 1px #ccc;
                padding: 5px 14px;
                background: #fcfcfc;
                margin-top: 4px;
            }

            .tm-install a {
                text-decoration: none;
            }

            .tm-install p {
                font-size: 1.1em;
            }

            .tm-install .actions {
                display: flex;
                justify-content: space-between;
            }

            .tm-install .actions button {
                font-weight: bold;
            }

            .tm-install .actions button:hover {
                cursor: pointer;
            }

            .tm-page {
                max-width: 1100px;
            }

            .tm-button {
                display: inline-block;
                padding: 0.5em 1.5em;
                border: 1px solid #ccc;
                background: #eee;
                text-decoration: none;
                font-weight: bold;
            }

            .tm-card {
                border: solid 1px #ccc;
                padding: 2em 1em 0;
                background: #fcfcfc;
                overflow: scroll;
            }

            .message {
                padding: 0.75em 2em;
                border: 1px solid #ccc;
                background: #efefef;
                font-weight: 500;
            }

            .message.success {
                background: #bbf2d1;
            }

            .form-section {
                margin-bottom: 1em;
                clear: both;
            }
            .form-section input[type=text] {
                margin: 0.5em 0;
                width: 50%;
            }
            @media only screen and (max-width: 600px) {
                .form-section input[type=text] {
                    width: 90%;
                }
            }
            .form-section label {
                font-weight: bold;
                font-size: 1.2em;
            }
            .form-section li {
                list-style-type: disc;
                list-style-position: inside;
            }
            .form-section .widget-preview {
                float: right;
                margin: 0 0.5em;
            }
            .form-section .submit {
                padding: 0.25em 0;
            }
        </style>'
    ;
}
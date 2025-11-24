import "./bootstrap";

import jquery from "jquery";
window.$ = jquery;
window.jQuery = jquery;

import "datatables.net-bs5";
import "../css/datatables.css";

import "jquery-mask-plugin";

import "sweetalert2/dist/sweetalert2.min.css";
import Swal from 'sweetalert2';
window.Swal = Swal;

import { Dropzone } from "dropzone";
import "../css/dropzone.css";
window.Dropzone = Dropzone;

// Choices.js - Vanilla JS select library
import Choices from 'choices.js';
import 'choices.js/public/assets/styles/choices.min.css';
window.Choices = Choices;


export default class FormValidator {
    constructor(formSelector, rules = {}, options = {}) {
        this.form = document.querySelector(formSelector);
        this.rules = rules;

        this.options = {
            realtime: true,
            autoDisable: true,
            messages: {},
            ...options
        };

        this.inputs = this.form.querySelectorAll("input, textarea, select");

        // FIXED: detect correct button by ID FIRST
        this.submitBtn =
            document.getElementById("submitBtn") ||
            this.form.querySelector("button[type='submit']");
    }

    /* Helper: Skip hidden fields */
    isVisible(input) {
        return !!input.offsetParent; // true only if user can see/focus it
    }

    getWrapper(input) {
        return input.closest(
            ".form-col-full, .form-col-half, .form-floating, .form-group, .terms-checkbox, .remember-me, .form-check"
        );
    }

    getCustomMessage(field, ruleName) {
        return this.options.messages?.[field]?.[ruleName] || null;
    }

    validateRule(value, ruleName, ruleValue, fieldName, input) {
        const customMsg = this.getCustomMessage(fieldName, ruleName);

        /*** SPECIAL RULE FOR AADHAAR ***/
        if (fieldName == "sender_document_number") {
            if (document.getElementById("sender_country_types").value == "14") {
                const reg = /^[0-9]{12}$/;
                if (!value) return customMsg || "This field is required";
                if (!reg.test(value)) return customMsg || "Aadhaar number must be 12 digits";
                if (/^(\d)\1{11}$/.test(value)) return customMsg || "Invalid Aadhaar number";
                return null;
            }
        }

        if (fieldName == "receiver_document_number") {
            if (document.getElementById("receiver_country_types").value == "14") {
                const reg = /^[0-9]{12}$/;
                if (!value) return customMsg || "This field is required";
                if (!reg.test(value)) return customMsg || "Aadhaar number must be 12 digits";
                if (/^(\d)\1{11}$/.test(value)) return customMsg || "Invalid Aadhaar number";
                return null;
            }
        }

        /*** FILE VALIDATION ***/
        if (input?.type === "file") {
            const file = input.files[0];

            if (ruleName === "required") {
                if (!file) return customMsg || "Please upload document";
                return null;
            }

            if (ruleName === "filetype") {
                const allowed = ruleValue.split(",");
                if (file && !allowed.includes(file.type)) {
                    return customMsg || `Allowed types: ${allowed.join(", ")}`;
                }
                return null;
            }

            if (ruleName === "max") {
                const maxBytes = Number(ruleValue) * 1024 * 1024;
                if (file && file.size > maxBytes) {
                    return customMsg || `File must be less than ${ruleValue} MB`;
                }
                return null;
            }
        }

        /*** NORMAL INPUT VALIDATION ***/
        if (ruleName === "required") {
            if (!value) return customMsg || "This field is required";
            return null;
        }

        if (ruleName === "email") {
            const reg = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!reg.test(value)) return customMsg || "Invalid email format";
            return null;
        }

        if (ruleName === "mobile") {
            // Optional country code: +xx or +xxx
            // Then 6–14 digits (with optional spaces)
            const reg = /^(?:\+[1-9][0-9]{0,2})?[0-9\s]{6,14}$/;

            if (!reg.test(value)) {
                return customMsg || "Invalid mobile number";
            }
            return null;
        }

        if (ruleName === "password") {
            const reg = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^\w\s]).{8,}$/;

            if (!reg.test(value)) {
                return customMsg || "Password must be at least 8 characters long, with uppercase, lowercase, number, and special character";
            }
            return null;
        }


        if (ruleName === "min") {
            if (value.length < ruleValue)
                return customMsg || `Minimum ${ruleValue} characters`;
            return null;
        }

        if (ruleName === "match") {
            const target = document.querySelector(ruleValue);
            if (!target) return null;
            if (target.value.trim() !== value)
                return customMsg || "Values do not match";
            return null;
        }

        if (ruleName === "gt") {
            if (isNaN(value)) {
                return "Value must be a number";
            }
            if (Number(value) <= Number(ruleValue))
                return customMsg || `Value must be greater than ${ruleValue}`;
            return null;
        }

        return null;
    }

    validateField(input) {
        if (!this.isVisible(input)) return true; // SKIP HIDDEN FIELDS

        const fieldName = input.name;

        // No rules → always valid
        if (!this.rules[fieldName]) {
            input.classList.add("is-valid");
            return true;
        }

        const rules = this.rules[fieldName].split("|");

        let value =
            input.type === "checkbox" ? input.checked : input.value.trim();

        for (let rule of rules) {
            let [ruleName, ruleValue] = rule.split(":");

            const error = this.validateRule(
                value,
                ruleName,
                ruleValue,
                fieldName,
                input
            );

            if (error) {
                this.setError(input, error);
                return false;
            }
        }

        this.clearError(input);
        return true;
    }

    setError(input, message) {
        const group = this.getWrapper(input);
        const msgEl = group.querySelector(".invalid-feedback");

        msgEl.innerText = message;

        input.classList.add("is-invalid");
        input.classList.remove("is-valid");
    }

    clearError(input) {
        const group = this.getWrapper(input);
        const msgEl = group.querySelector(".invalid-feedback");

        msgEl.innerText = "";

        input.classList.remove("is-invalid");
        input.classList.add("is-valid");
    }

    attachRealtimeValidation() {
        if (!this.options.realtime) return;

        this.inputs.forEach((input) => {
            let events = [];

            if (input.tagName === "SELECT") {
                events = ["change", "blur"];
            } else if (input.type === "checkbox" || input.type === "file") {
                events = ["change"];
            } else {
                events = ["keyup", "blur"];
            }

            events.forEach((evt) => {
                input.addEventListener(evt, () => {
                    if (!this.isVisible(input)) return; // SKIP hidden
                    this.validateField(input);
                    this.checkFormValid();
                });
            });
        });
    }

    checkFormValid() {
        if (!this.options.autoDisable) return;

        const allValid = [...this.inputs]
            .filter((i) => this.rules[i.name]) // only fields with rules
            .filter((i) => this.isVisible(i))   // FIX: only visible
            .every((i) => i.classList.contains("is-valid"));

        this.submitBtn.disabled = !allValid;
    }

    init() {
        this.attachRealtimeValidation();
        this.observeDynamicFields();
        this.checkFormValid();
    }

    handleSubmit(e) {
        let valid = true;

        this.inputs.forEach((input) => {
            if (!this.isVisible(input)) return; // SKIP hidden fields
            if (!this.validateField(input)) valid = false;
        });

        this.checkFormValid();
        return valid;
    }

    triggerValidation() {
        this.inputs.forEach((input) => {
            if (!this.isVisible(input)) return;
            this.validateField(input);
        });

        this.checkFormValid();
    }

    observeDynamicFields() {
        const observer = new MutationObserver((mutations) => {
            mutations.forEach((m) => {
                m.addedNodes.forEach((node) => {
                    if (node.nodeType === 1) {
                        const newInputs = node.querySelectorAll(
                            "input, textarea, select"
                        );

                        if (newInputs.length > 0) {
                            this.inputs = this.form.querySelectorAll(
                                "input, textarea, select"
                            );

                            this.attachRealtimeValidation();
                        }
                    }
                });
            });
        });

        observer.observe(this.form, {
            childList: true,
            subtree: true,
        });
    }
}

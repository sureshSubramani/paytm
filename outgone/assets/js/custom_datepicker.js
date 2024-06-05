(function () {
    var input = document.getElementById('myInput');

    var datepicker = new TheDatepicker.Datepicker(input);

    var selectedDateOutput = document.getElementById('selectedDate');
    var selectedDateFormattedOutput = document.getElementById('selectedDateFormatted');
    var updateSelectedDate = function () {
        var selectedDate = datepicker.getSelectedDate();
        selectedDateOutput.innerText = selectedDate !== null ? '.toString() === "' + selectedDate.toString() + '"' : ' === null';
        var selectedDateFormatted = datepicker.getSelectedDateFormatted();
        selectedDateFormattedOutput.innerText = selectedDateFormatted !== null ? '"' + selectedDateFormatted + '"' : 'null';
    };
    datepicker.options.onSelect(updateSelectedDate);
    updateSelectedDate();

    var currentMonthOutput = document.getElementById('currentMonth');
    var updateCurrentMonth = function () {
        var currentMonth = datepicker.getCurrentMonth();
        currentMonthOutput.innerText = currentMonth !== null ? '.toString() === "' + currentMonth.toString() + '"' : ' === null';
    };
    datepicker.options.onMonthChange(updateCurrentMonth);
    updateCurrentMonth();

    datepicker.render();

    var updateSetting = function (settingInput, callback, doNotCallRender) {
        settingInput.className = '';
        var errorOutput = document.getElementById(settingInput.id + 'Error');
        if (errorOutput !== null) {
            errorOutput.innerText = '';
        }

        try {
            callback();
            if (!doNotCallRender) {
                datepicker.render();
            }
        } catch (error) {
            console.error(error);
            settingInput.className = 'invalid';
            if (errorOutput !== null) {
                errorOutput.innerText = error.toString();
            }
        }
    };

    var createCallback = function (argumentsString, body) {
        return (new Function('{ return function(' + argumentsString + ') { ' + body + ' } };'))();
    };

    var updateListener = function (eventName, body, argumentsString) {
        var eventNameCapitalized = eventName.charAt(0).toUpperCase() + eventName.slice(1);

        datepicker.options['off' + eventNameCapitalized]();

        if (eventName === 'select') {
            datepicker.options.onSelect(updateSelectedDate);
        } else if (eventName === 'monthChange') {
            datepicker.options.onMonthChange(updateCurrentMonth);
        }

        if (body === '') {
            return;
        }

        datepicker.options['on' + eventNameCapitalized](createCallback(argumentsString, body));
    };

    var removeListener = function (eventName) {
        updateListener(eventName, '');
        var eventNameCapitalized = eventName.charAt(0).toUpperCase() + eventName.slice(1);
        document.getElementById('on' + eventNameCapitalized).value = '';
    };

    var datepicker1Text = document.getElementById('datepicker1');
    var datepicker2Input = document.getElementById('datepicker2');
    datepicker2Input.onclick = function () {
        datepicker = new TheDatepicker.Datepicker(input, null, datepicker.options);
        datepicker1Text.className = '';
        datepicker2Input.className = 'hidden';
        datepicker.render();
    };

    var initialDateInput = document.getElementById('initialDate');
    initialDateInput.onchange = function () {
        updateSetting(initialDateInput, function () {
            datepicker.options.setInitialDate(initialDateInput.value !== '' ? initialDateInput.value : null);
        });
    };

    var initialMonthInput = document.getElementById('initialMonth');
    initialMonthInput.onchange = function () {
        updateSetting(initialMonthInput, function () {
            datepicker.options.setInitialMonth(initialMonthInput.value !== '' ? initialMonthInput.value : null);
        });
    };

    var initialDatePriorityInput = document.getElementById('initialDatePriority');
    initialDatePriorityInput.onchange = function () {
        updateSetting(initialDatePriorityInput, function () {
            datepicker.options.setInitialDatePriority(initialDatePriorityInput.checked);
        });
    };

    var openInput = document.getElementById('open');
    openInput.onclick = function () {
        datepicker.open();
    };

    var closeInput = document.getElementById('close');
    closeInput.onclick = function () {
        datepicker.close();
    };

    var resetInput = document.getElementById('reset');
    resetInput.onclick = function () {
        datepicker.reset();
    };

    var destroyInput = document.getElementById('destroy');
    destroyInput.onclick = function () {
        datepicker.destroy();
        datepicker1Text.className = 'hidden';
        datepicker2Input.className = '';
    };

    var selectDateInput = document.getElementById('selectDate');
    selectDateInput.onchange = function () {
        updateSetting(selectDateInput, function () {
            datepicker.selectDate(selectDateInput.value, document.getElementById('selectDateDoUpdateMonth').checked);
            selectDateInput.value = '';
        }, true);
    };

    var goToMonthInput = document.getElementById('goToMonth');
    goToMonthInput.onchange = function () {
        updateSetting(goToMonthInput, function () {
            datepicker.goToMonth(goToMonthInput.value);
            goToMonthInput.value = '';
        }, true);
    };

    var hideOnBlurInput = document.getElementById('hideOnBlur');
    hideOnBlurInput.onchange = function () {
        updateSetting(hideOnBlurInput, function () {
            datepicker.options.setHideOnBlur(hideOnBlurInput.checked);
        });
    };

    var hideOnSelectInput = document.getElementById('hideOnSelect');
    hideOnSelectInput.onchange = function () {
        updateSetting(hideOnSelectInput, function () {
            datepicker.options.setHideOnSelect(hideOnSelectInput.checked);
        });
    };

    var inputFormatInput = document.getElementById('inputFormat');
    inputFormatInput.onchange = function () {
        updateSetting(inputFormatInput, function () {
            datepicker.options.setInputFormat(inputFormatInput.value);
            updateSelectedDate();
        });
    };

    var firstDayOfWeekInput = document.getElementById('firstDayOfWeek');
    firstDayOfWeekInput.onchange = function () {
        updateSetting(firstDayOfWeekInput, function () {
            datepicker.options.setFirstDayOfWeek(firstDayOfWeekInput.value);
        });
    };

    var minDateInput = document.getElementById('minDate');
    minDateInput.onchange = function () {
        updateSetting(minDateInput, function () {
            datepicker.options.setMinDate(minDateInput.value !== '' ? minDateInput.value : null);
        });
    };

    var maxDateInput = document.getElementById('maxDate');
    maxDateInput.onchange = function () {
        updateSetting(maxDateInput, function () {
            datepicker.options.setMaxDate(maxDateInput.value !== '' ? maxDateInput.value : null);
        });
    };

    var dropdownItemsLimitInput = document.getElementById('dropdownItemsLimit');
    dropdownItemsLimitInput.onchange = function () {
        updateSetting(dropdownItemsLimitInput, function () {
            datepicker.options.setDropdownItemsLimit(dropdownItemsLimitInput.value !== '' ? dropdownItemsLimitInput.value : null);
        });
    };

    var hideDropdownWithOneItemInput = document.getElementById('hideDropdownWithOneItem');
    hideDropdownWithOneItemInput.onchange = function () {
        updateSetting(hideDropdownWithOneItemInput, function () {
            datepicker.options.setHideDropdownWithOneItem(hideDropdownWithOneItemInput.checked);
        });
    };

    var daysOutOfMonthVisibleInput = document.getElementById('daysOutOfMonthVisible');
    daysOutOfMonthVisibleInput.onchange = function () {
        updateSetting(daysOutOfMonthVisibleInput, function () {
            datepicker.options.setDaysOutOfMonthVisible(daysOutOfMonthVisibleInput.checked);
        });
    };

    var fixedRowsCountInput = document.getElementById('fixedRowsCount');
    fixedRowsCountInput.onchange = function () {
        updateSetting(fixedRowsCountInput, function () {
            datepicker.options.setFixedRowsCount(fixedRowsCountInput.checked);
        });
    };

    var toggleSelectionInput = document.getElementById('toggleSelection');
    toggleSelectionInput.onchange = function () {
        updateSetting(toggleSelectionInput, function () {
            datepicker.options.setToggleSelection(toggleSelectionInput.checked);
        });
    };

    var showDeselectButtonInput = document.getElementById('showDeselectButton');
    showDeselectButtonInput.onchange = function () {
        updateSetting(showDeselectButtonInput, function () {
            datepicker.options.setShowDeselectButton(showDeselectButtonInput.checked);
        });
    };

    var allowEmptyInput = document.getElementById('allowEmpty');
    allowEmptyInput.onchange = function () {
        updateSetting(allowEmptyInput, function () {
            datepicker.options.setAllowEmpty(allowEmptyInput.checked);
        });
    };

    var showCloseButtonInput = document.getElementById('showCloseButton');
    showCloseButtonInput.onchange = function () {
        updateSetting(showCloseButtonInput, function () {
            datepicker.options.setShowCloseButton(showCloseButtonInput.checked);
        });
    };

    var titleInput = document.getElementById('title');
    titleInput.onchange = function () {
        updateSetting(titleInput, function () {
            datepicker.options.setTitle(titleInput.value);
        });
    };

    var showResetButtonInput = document.getElementById('showResetButton');
    showResetButtonInput.onchange = function () {
        updateSetting(showResetButtonInput, function () {
            datepicker.options.setShowResetButton(showResetButtonInput.checked);
        });
    };

    var monthAsDropdownInput = document.getElementById('monthAsDropdown');
    monthAsDropdownInput.onchange = function () {
        updateSetting(monthAsDropdownInput, function () {
            datepicker.options.setMonthAsDropdown(monthAsDropdownInput.checked);
        });
    };

    var yearAsDropdownInput = document.getElementById('yearAsDropdown');
    yearAsDropdownInput.onchange = function () {
        updateSetting(yearAsDropdownInput, function () {
            datepicker.options.setYearAsDropdown(yearAsDropdownInput.checked);
        });
    };

    var monthAndYearSeparatedInput = document.getElementById('monthAndYearSeparated');
    monthAndYearSeparatedInput.onchange = function () {
        updateSetting(monthAndYearSeparatedInput, function () {
            datepicker.options.setMonthAndYearSeparated(monthAndYearSeparatedInput.checked);
        });
    };

    var positionFixingInput = document.getElementById('positionFixing');
    positionFixingInput.onchange = function () {
        updateSetting(positionFixingInput, function () {
            datepicker.options.setPositionFixing(positionFixingInput.checked);
        });
    };

    var dateAvailabilityResolverInput = document.getElementById('dateAvailabilityResolver');
    dateAvailabilityResolverInput.onchange = function () {
        if (dateAvailabilityResolverInput.value === '') {
            datepicker.options.setDateAvailabilityResolver(null);
        } else {
            datepicker.options.setDateAvailabilityResolver(createCallback('date', dateAvailabilityResolverInput.value));
        }
        datepicker.render();
    };

    var cellContentResolverInput = document.getElementById('cellContentResolver');
    cellContentResolverInput.onchange = function () {
        if (cellContentResolverInput.value === '') {
            datepicker.options.setCellContentResolver(null);
        } else {
            datepicker.options.setCellContentResolver(createCallback('day', cellContentResolverInput.value));
        }
        datepicker.render();
    };

    var cellContentStructureResolverInitInput = document.getElementById('cellContentStructureResolverInit');
    var cellContentStructureResolverUpdateInput = document.getElementById('cellContentStructureResolverUpdate');
    var cellContentStructureResolverUpdateListener = function () {
        if (cellContentStructureResolverInitInput.value === '' || cellContentStructureResolverUpdateInput.value === '') {
            datepicker.options.setCellContentStructureResolver(null);
        } else {
            datepicker.options.setCellContentStructureResolver(
                createCallback('', cellContentStructureResolverInitInput.value),
                createCallback('element, day', cellContentStructureResolverUpdateInput.value)
            );
        }
        datepicker.render();
    };
    cellContentStructureResolverInitInput.onchange = cellContentStructureResolverUpdateListener;
    cellContentStructureResolverUpdateInput.onchange = cellContentStructureResolverUpdateListener;

    var cellClassesResolverInput = document.getElementById('cellClassesResolver');
    cellClassesResolverInput.onchange = function () {
        datepicker.options.removeCellClassesResolver();
        if (cellClassesResolverInput.value !== '') {
            datepicker.options.addCellClassesResolver(createCallback('day', cellClassesResolverInput.value));
        }
        datepicker.render();
    };

    var dayModifierInput = document.getElementById('dayModifier');
    dayModifierInput.onchange = function () {
        datepicker.options.removeDayModifier();
        if (dayModifierInput.value !== '') {
            datepicker.options.addDayModifier(createCallback('day', dayModifierInput.value));
        }
        datepicker.render();
    };

    var headerStructureResolverInput = document.getElementById('headerStructureResolver');
    headerStructureResolverInput.onchange = function () {
        if (headerStructureResolverInput.value === '') {
            datepicker.options.setHeaderStructureResolver(null);
        } else {
            datepicker.options.setHeaderStructureResolver(createCallback('', headerStructureResolverInput.value));
        }
    };

    var footerStructureResolverInput = document.getElementById('footerStructureResolver');
    footerStructureResolverInput.onchange = function () {
        if (footerStructureResolverInput.value === '') {
            datepicker.options.setFooterStructureResolver(null);
        } else {
            datepicker.options.setFooterStructureResolver(createCallback('', footerStructureResolverInput.value));
        }
    };

    var onBeforeSelectInput = document.getElementById('onBeforeSelect');
    onBeforeSelectInput.onchange = function () {
        updateListener('beforeSelect', onBeforeSelectInput.value, 'event, day, previousDay');
    };

    var onSelectInput = document.getElementById('onSelect');
    onSelectInput.onchange = function () {
        updateListener('select', onSelectInput.value, 'event, day, previousDay');
    };

    var onBeforeOpenAndCloseInput = document.getElementById('onBeforeOpenAndClose');
    onBeforeOpenAndCloseInput.onchange = function () {
        updateListener('beforeOpenAndClose', onBeforeOpenAndCloseInput.value, 'event, isOpening');
    };

    var onOpenAndCloseInput = document.getElementById('onOpenAndClose');
    onOpenAndCloseInput.onchange = function () {
        updateListener('openAndClose', onOpenAndCloseInput.value, 'event, isOpening');
    };

    var onBeforeMonthChangeInput = document.getElementById('onBeforeMonthChange');
    onBeforeMonthChangeInput.onchange = function () {
        updateListener('beforeMonthChange', onBeforeMonthChangeInput.value, 'event, month, previousMonth');
    };

    var onMonthChangeInput = document.getElementById('onMonthChange');
    onMonthChangeInput.onchange = function () {
        updateListener('monthChange', onMonthChangeInput.value, 'event, month, previousMonth');
    };

    var onBeforeFocusInput = document.getElementById('onBeforeFocus');
    onBeforeFocusInput.onchange = function () {
        updateListener('beforeFocus', onBeforeFocusInput.value, 'event, day, previousDay');
    };

    var onFocusInput = document.getElementById('onFocus');
    onFocusInput.onchange = function () {
        updateListener('focus', onFocusInput.value, 'event, day, previousDay');
    };

    var removeCellClassesResolverInput = document.getElementById('removeCellClassesResolver');
    removeCellClassesResolverInput.onclick = function () {
        datepicker.options.removeCellClassesResolver();
        document.getElementById('cellClassesResolver').value = '';
    };

    var removeDayModifierInput = document.getElementById('removeDayModifier');
    removeDayModifierInput.onclick = function () {
        datepicker.options.removeDayModifier();
        document.getElementById('dayModifier').value = '';
    };

    var offBeforeSelectInput = document.getElementById('offBeforeSelect');
    offBeforeSelectInput.onclick = function () {
        removeListener('beforeSelect');
    };

    var offSelectInput = document.getElementById('offSelect');
    offSelectInput.onclick = function () {
        removeListener('select');
    };

    var offBeforeOpenAndCloseInput = document.getElementById('offBeforeOpenAndClose');
    offBeforeOpenAndCloseInput.onclick = function () {
        removeListener('beforeOpenAndClose');
    };

    var offOpenAndCloseInput = document.getElementById('offOpenAndClose');
    offOpenAndCloseInput.onclick = function () {
        removeListener('openAndClose');
    };

    var offBeforeMonthChangeInput = document.getElementById('offBeforeMonthChange');
    offBeforeMonthChangeInput.onclick = function () {
        removeListener('beforeMonthChange');
    };

    var offMonthChangeInput = document.getElementById('offMonthChange');
    offMonthChangeInput.onclick = function () {
        removeListener('monthChange');
    };

    var offBeforeFocusInput = document.getElementById('offBeforeFocus');
    offBeforeFocusInput.onclick = function () {
        removeListener('beforeFocus');
    };

    var offFocusInput = document.getElementById('offFocus');
    offFocusInput.onclick = function () {
        removeListener('focus');
    };

    var classesPrefixInput = document.getElementById('classesPrefix');
    classesPrefixInput.onchange = function () {
        updateSetting(classesPrefixInput, function () {
            datepicker.options.setClassesPrefix(classesPrefixInput.value);
        });
    };

    var todayInput = document.getElementById('today');
    todayInput.onchange = function () {
        updateSetting(todayInput, function () {
            datepicker.options.setToday(todayInput.value);
        });
    };

    var goBackHtmlInput = document.getElementById('goBackHtml');
    goBackHtmlInput.onchange = function () {
        updateSetting(goBackHtmlInput, function () {
            datepicker.options.setGoBackHtml(goBackHtmlInput.value);
        });
    };

    var goForwardHtmlInput = document.getElementById('goForwardHtml');
    goForwardHtmlInput.onchange = function () {
        updateSetting(goForwardHtmlInput, function () {
            datepicker.options.setGoForwardHtml(goForwardHtmlInput.value);
        });
    };

    var closeHtmlInput = document.getElementById('closeHtml');
    closeHtmlInput.onchange = function () {
        updateSetting(closeHtmlInput, function () {
            datepicker.options.setCloseHtml(closeHtmlInput.value);
        });
    };

    var resetHtmlInput = document.getElementById('resetHtml');
    resetHtmlInput.onchange = function () {
        updateSetting(resetHtmlInput, function () {
            datepicker.options.setResetHtml(resetHtmlInput.value);
        });
    };

    var deselectHtmlInput = document.getElementById('deselectHtml');
    deselectHtmlInput.onchange = function () {
        updateSetting(deselectHtmlInput, function () {
            datepicker.options.setReselectHtml(deselectHtmlInput.value);
        });
    };

    for (var i = 0; i < 7; i++) {
        (function () {
            var dayOfWeek = i;
            var dayOfWeekTranslationInput = document.getElementById('dayOfWeekTranslation' + dayOfWeek);
            dayOfWeekTranslationInput.onchange = function () {
                updateSetting(dayOfWeekTranslationInput, function () {
                    datepicker.options.translator.setDayOfWeekTranslation(dayOfWeek, dayOfWeekTranslationInput.value);
                });
            };
        })();
    }

    for (var j = 0; j < 12; j++) {
        (function () {
            var month = j;
            var monthTranslationInput = document.getElementById('monthTranslation' + month);
            monthTranslationInput.onchange = function () {
                updateSetting(monthTranslationInput, function () {
                    datepicker.options.translator.setMonthTranslation(month, monthTranslationInput.value);
                });
            };
        })();
    }

    var onDatepickerReadyInput = document.getElementById('onDatepickerReady');
    onDatepickerReadyInput.onchange = function () {
        if (onDatepickerReadyInput.value !== '') {
            TheDatepicker.onDatepickerReady(input, createCallback('datepicker', onDatepickerReadyInput.value));
        }
    };

    var datepickerPromiseInput = document.getElementById('datepickerPromise');
    datepickerPromiseInput.onchange = function () {
        if (datepickerPromiseInput.value !== '') {
            (new Function('{ return async function(datepickerPromise) { ' + datepickerPromiseInput.value + ' } };'))()(TheDatepicker.onDatepickerReady(input));
        }
    };

})();
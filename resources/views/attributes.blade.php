<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>نظام إدارة السمات</title>
    <style>
        :root {
            --primary-color: #4a6baf;
            --secondary-color: #f8f9fa;
            --danger-color: #dc3545;
            --success-color: #28a745;
            --warning-color: #ffc107;
            --border-color: #dee2e6;
            --shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            padding: 20px;
            background: #f5f7fa;
            direction: rtl;
            color: #333;
            line-height: 1.6;
        }

        .container {
            max-width: 1000px;
            margin: 0 auto;
        }

        h1 {
            color: #4a6baf;
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 15px;
            border-bottom: 2px solid #4a6baf;
        }

        .header-actions {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .btn {
            padding: 8px 16px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-weight: 500;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }

        .btn-primary {
            background-color: #4a6baf;
            color: white;
        }

        .btn-primary:hover {
            background-color: #3a5a9f;
            transform: translateY(-2px);
        }

        .btn-danger {
            background-color:  #dc3545;
            color: white;
        }

        .btn-danger:hover {
            background-color: #c82333;
        }

        .btn-warning {
            background-color: #ffc107;
            color: #212529;
        }

        .btn-warning:hover {
            background-color: #e0a800;
        }

        .btn-sm {
            padding: 5px 10px;
            font-size: 0.85rem;
        }

        .attribute, .option {
            background: white;
            margin-bottom: 15px;
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }

        .attribute:hover {
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
        }

        .attribute-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            cursor: pointer;
            padding: 5px 0;
        }

        .attribute-header h3 {
            margin: 0;
            font-size: 18px;
            color: #4a6baf;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .attribute-actions {
            display: flex;
            gap: 8px;
        }

        .options {
            display: none;
            margin-top: 15px;
            padding-right: 10px;
            border-top: 1px solid #dee2e6;
            padding-top: 15px;
        }

        .options.show {
            display: block;
        }

        .option {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border: 1px solid #dee2e6;
            border-radius: 6px;
            padding: 12px 15px;
            margin-bottom: 10px;
            background-color: #f8f9fa;
        }

        .option:last-child {
            margin-bottom: 0;
        }

        .option-value {
            font-weight: 500;
        }

        .option-actions {
            display: flex;
            gap: 8px;
        }

        .add-option-btn {
            margin-top: 10px;
            background-color: #e9ecef;
            color: #495057;
        }

        .add-option-btn:hover {
            background-color: #d1d7e0;
        }

        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            right: 0;
            bottom: 0;
            left: 0;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 1000;
            align-items: center;
            justify-content: center;
        }

        .modal-content {
            background: white;
            padding: 25px;
            border-radius: 8px;
            width: 90%;
            max-width: 500px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 1px solid #dee2e6;
        }

        .modal-header h2 {
            margin: 0;
            color: #4a6baf;
        }

        .close-btn {
            background: none;
            border: none;
            font-size: 1.5rem;
            cursor: pointer;
            color: #6c757d;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: 500;
        }

        .form-control {
            width: 100%;
            padding: 8px 12px;
            border: 1px solid #dee2e6;
            border-radius: 4px;
            font-size: 1rem;
        }

        .form-control:focus {
            outline: none;
            border-color: #4a6baf;
            box-shadow: 0 0 0 2px rgba(74, 107, 175, 0.25);
        }

        .modal-footer {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            margin-top: 20px;
            padding-top: 15px;
            border-top: 1px solid #dee2e6;
        }

        .options-list {
            margin-top: 15px;
        }

        .option-input-group {
            display: flex;
            gap: 10px;
            margin-bottom: 10px;
        }

        .option-input-group input {
            flex: 1;
        }

        .remove-option-btn {
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            color:  #dc3545;
            width: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .add-more-options {
            margin-top: 10px;
        }

        .empty-state {
            text-align: center;
            padding: 40px 20px;
            color: #6c757d;
        }

        .empty-state p {
            margin-bottom: 20px;
        }

        @media (max-width: 768px) {
            .attribute-header, .option {
                flex-direction: column;
                align-items: flex-start;
                gap: 10px;
            }

            .attribute-actions, .option-actions {
                width: 100%;
                justify-content: flex-end;
            }
        }
    </style>
</head>
<body>
<div class="container">
    <h1>🧩 نظام إدارة السمات</h1>

    <div class="header-actions">
        <button id="add-attribute-btn" class="btn btn-primary">
            <span>➕</span> إضافة سمة جديدة
        </button>
    </div>

    <div id="attributes-list">
        <!-- سيتم إدخال السمات هنا عبر JS -->
        <div class="text-center">جاري تحميل البيانات...</div>
    </div>

    <!-- Modal لإضافة/تعديل السمة -->
    <div id="attribute-modal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 id="modal-title">إضافة سمة جديدة</h2>
                <button class="close-btn">&times;</button>
            </div>
            <form id="attribute-form">
                <input type="hidden" id="attribute-id">

                <div class="form-group">
                    <label for="name_ar">اسم السمة (العربية)</label>
                    <input type="text" id="name_ar" class="form-control" required>
                </div>

                <div class="form-group">
                    <label for="name_en">اسم السمة (الإنجليزية)</label>
                    <input type="text" id="name_en" class="form-control" required>
                </div>

                <div class="form-group">
                    <label>قيم السمة</label>
                    <div id="options-list" class="options-list">
                        <!-- سيتم إضافة حقول القيم هنا -->
                    </div>
                    <button type="button" id="add-more-options" class="btn add-more-options">
                        <span>➕</span> إضافة قيمة أخرى
                    </button>
                </div>

                <div class="modal-footer">
                    <button type="button" id="cancel-btn" class="btn">إلغاء</button>
                    <button type="submit" class="btn btn-primary">حفظ</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal لإضافة/تعديل القيمة -->
    <div id="option-modal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 id="option-modal-title">إضافة قيمة جديدة</h2>
                <button class="close-btn">&times;</button>
            </div>
            <form id="option-form">
                <input type="hidden" id="option-id">
                <input type="hidden" id="option-attribute-id">

                <div class="form-group">
                    <label for="option_value_ar">القيمة (العربية)</label>
                    <input type="text" id="option_value_ar" class="form-control" required>
                </div>

                <div class="form-group">
                    <label for="option_value_en">القيمة (الإنجليزية)</label>
                    <input type="text" id="option_value_en" class="form-control" required>
                </div>

                <div class="modal-footer">
                    <button type="button" id="option-cancel-btn" class="btn">إلغاء</button>
                    <button type="submit" class="btn btn-primary">حفظ</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // عناصر DOM
    const attributesList = document.getElementById('attributes-list');
    const addAttributeBtn = document.getElementById('add-attribute-btn');
    const attributeModal = document.getElementById('attribute-modal');
    const optionModal = document.getElementById('option-modal');
    const attributeForm = document.getElementById('attribute-form');
    const optionForm = document.getElementById('option-form');
    const modalTitle = document.getElementById('modal-title');
    const optionsList = document.getElementById('options-list');
    const addMoreOptionsBtn = document.getElementById('add-more-options');
    const closeBtns = document.querySelectorAll('.close-btn, #cancel-btn, #option-cancel-btn');

    // متغيرات الحالة
    let currentAttributeId = null;
    let currentOptionId = null;
    let isEditing = false;

    // تهيئة التطبيق
    function init() {
        fetchAttributes();
        setupEventListeners();
    }

    // جلب السمات من الخادم
    function fetchAttributes() {
        fetch('/api/attributes')
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                if (data.length === 0) {
                    showEmptyState();
                } else {
                    renderAttributesList(data);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                attributesList.innerHTML = `
                        <div class="empty-state">
                            <p>حدث خطأ أثناء جلب البيانات. يرجى المحاولة مرة أخرى.</p>
                            <button onclick="fetchAttributes()" class="btn btn-primary">إعادة المحاولة</button>
                        </div>
                    `;
            });
    }

    // عرض حالة عدم وجود بيانات
    function showEmptyState() {
        attributesList.innerHTML = `
                <div class="empty-state">
                    <p>لا توجد سمات متاحة حالياً</p>
                    <button id="add-first-attribute" class="btn btn-primary">إضافة سمة جديدة</button>
                </div>
            `;
        document.getElementById('add-first-attribute').addEventListener('click', () => {
            openAttributeModal();
        });
    }

    // عرض قائمة السمات
    function renderAttributesList(attributes) {
        attributesList.innerHTML = '';

        attributes.forEach(attr => {
            const attrDiv = document.createElement('div');
            attrDiv.className = 'attribute';

            attrDiv.innerHTML = `
                    <div class="attribute-header" onclick="toggleOptions(${attr.id})">
                        <h3>
                            <span class="attribute-icon">🟢</span>
                            ${attr.name_ar} | ${attr.name_en}
                            <span class="options-count">(${attr.options.length} قيم)</span>
                        </h3>
                        <div class="attribute-actions">
                            <button class="btn btn-warning btn-sm" onclick="event.stopPropagation(); editAttribute(${attr.id})">
                                <span>✏️</span> تعديل
                            </button>
                            <button class="btn btn-danger btn-sm" onclick="event.stopPropagation(); deleteAttribute(${attr.id})">
                                <span>🗑️</span> حذف
                            </button>
                            <button class="btn btn-sm" onclick="event.stopPropagation(); toggleOptions(${attr.id})">
                                <span class="toggle-icon">🔽</span> القيم
                            </button>
                        </div>
                    </div>
                    <div class="options" id="options-${attr.id}">
                        ${attr.options.map(opt => `
                            <div class="option">
                                <div class="option-value">
                                    <strong>${opt.value_ar} | ${opt.value_en}</strong>
                                </div>
                                <div class="option-actions">
                                    <button class="btn btn-warning btn-sm" onclick="event.stopPropagation(); editOption(${attr.id}, ${opt.id})">
                                        <span>✏️</span> تعديل
                                    </button>
                                    <button class="btn btn-danger btn-sm" onclick="event.stopPropagation(); deleteOption(${attr.id}, ${opt.id})">
                                        <span>🗑️</span> حذف
                                    </button>
                                </div>
                            </div>
                        `).join('')}
                        <button class="btn btn-sm add-option-btn" onclick="event.stopPropagation(); addNewOption(${attr.id})">
                            <span>➕</span> إضافة قيمة جديدة
                        </button>
                    </div>
                `;

            attributesList.appendChild(attrDiv);
        });
    }

    // إعداد مستمعي الأحداث
    function setupEventListeners() {
        // فتح نموذج إضافة سمة
        addAttributeBtn.addEventListener('click', openAttributeModal);

        // إضافة سمة جديدة
        attributeForm.addEventListener('submit', handleAttributeSubmit);

        // إضافة خيارات أكثر
        addMoreOptionsBtn.addEventListener('click', addOptionInput);

        // إغلاق النماذج
        closeBtns.forEach(btn => {
            btn.addEventListener('click', closeAllModals);
        });

        // إضافة/تعديل قيمة
        optionForm.addEventListener('submit', handleOptionSubmit);

        // إغلاق بالنقر خارج النموذج
        window.addEventListener('click', (e) => {
            if (e.target === attributeModal || e.target === optionModal) {
                closeAllModals();
            }
        });
    }

    // فتح نموذج السمة
    function openAttributeModal(attributeId = null) {
        currentAttributeId = attributeId;
        isEditing = attributeId !== null;

        if (isEditing) {
            fetch(`/api/attributes/${attributeId}`)
                .then(response => response.json())
                .then(attribute => {
                    modalTitle.textContent = 'تعديل السمة';
                    document.getElementById('attribute-id').value = attribute.id;
                    document.getElementById('name_ar').value = attribute.name_ar;
                    document.getElementById('name_en').value = attribute.name_en;

                    // تعبئة الخيارات
                    optionsList.innerHTML = '';
                    attribute.options.forEach(opt => {
                        addOptionInput(opt.value_ar, opt.value_en);
                    });
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('حدث خطأ أثناء جلب بيانات السمة');
                    closeAllModals();
                });
        } else {
            modalTitle.textContent = 'إضافة سمة جديدة';
            document.getElementById('attribute-id').value = '';
            document.getElementById('name_ar').value = '';
            document.getElementById('name_en').value = '';
            optionsList.innerHTML = '';
            // إضافة حقل خيار واحد على الأقل
            addOptionInput();
        }

        attributeModal.style.display = 'flex';
    }

    // فتح نموذج القيمة
    function openOptionModal(attributeId, optionId = null) {
        currentAttributeId = attributeId;
        currentOptionId = optionId;
        isEditing = optionId !== null;

        if (isEditing) {
            fetch(`/api/attributes/${attributeId}/options/${optionId}`)
                .then(response => response.json())
                .then(option => {
                    document.getElementById('option-modal-title').textContent = 'تعديل القيمة';
                    document.getElementById('option-id').value = option.id;
                    document.getElementById('option-attribute-id').value = attributeId;
                    document.getElementById('option_value_ar').value = option.value_ar;
                    document.getElementById('option_value_en').value = option.value_en;
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('حدث خطأ أثناء جلب بيانات القيمة');
                    closeAllModals();
                });
        } else {
            document.getElementById('option-modal-title').textContent = 'إضافة قيمة جديدة';
            document.getElementById('option-id').value = '';
            document.getElementById('option-attribute-id').value = attributeId;
            document.getElementById('option_value_ar').value = '';
            document.getElementById('option_value_en').value = '';
        }

        optionModal.style.display = 'flex';
    }

    // إغلاق جميع النماذج
    function closeAllModals() {
        attributeModal.style.display = 'none';
        optionModal.style.display = 'none';
    }

    // إضافة حقل إدخال للخيار
    function addOptionInput(valueAr = '', valueEn = '') {
        const optionDiv = document.createElement('div');
        optionDiv.className = 'option-input-group';
        optionDiv.innerHTML = `
                <input type="text" class="form-control" placeholder="القيمة (عربي)"
                       value="${valueAr}" data-lang="ar" required>
                <input type="text" class="form-control" placeholder="القيمة (إنجليزي)"
                       value="${valueEn}" data-lang="en" required>
                <button type="button" class="btn remove-option-btn" onclick="removeOptionInput(this)">
                    <span>✖</span>
                </button>
            `;
        optionsList.appendChild(optionDiv);
    }

    // إزالة حقل إدخال الخيار
    function removeOptionInput(btn) {
        btn.parentElement.remove();
    }

    // معالجة حفظ السمة
    function handleAttributeSubmit(e) {
        e.preventDefault();

        const id = currentAttributeId;
        const name_ar = document.getElementById('name_ar').value.trim();
        const name_en = document.getElementById('name_en').value.trim();

        // جمع الخيارات
        const optionInputs = document.querySelectorAll('.option-input-group');
        const options = [];

        optionInputs.forEach(group => {
            const inputs = group.querySelectorAll('input');
            const value_ar = inputs[0].value.trim();
            const value_en = inputs[1].value.trim();

            if (value_ar && value_en) {
                options.push({ value_ar, value_en });
            }
        });

        if (options.length === 0) {
            alert('يجب إضافة قيمة واحدة على الأقل للسمة');
            return;
        }

        const url = isEditing ? `/api/attributes/${id}` : '/api/attributes';
        const method = isEditing ? 'PUT' : 'POST';

        fetch(url, {
            method: method,
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                name_ar,
                name_en,
                options: isEditing ? null : options // عند التعديل لا نرسل الخيارات
            })
        })
            .then(response => {
                if (!response.ok) {
                    return response.json().then(err => { throw err; });
                }
                return response.json();
            })
            .then(data => {
                closeAllModals();
                fetchAttributes();
            })
            .catch(error => {
                console.error('Error:', error);
                alert(error.message || 'حدث خطأ أثناء حفظ السمة');
            });
    }

    // معالجة حفظ القيمة
    function handleOptionSubmit(e) {
        e.preventDefault();

        const attributeId = parseInt(document.getElementById('option-attribute-id').value);
        const optionId = currentOptionId;
        const value_ar = document.getElementById('option_value_ar').value.trim();
        const value_en = document.getElementById('option_value_en').value.trim();

        const url = isEditing
            ? `/api/attributes/${attributeId}/options/${optionId}`
            : `/api/attributes/${attributeId}/options`;
        const method = isEditing ? 'PUT' : 'POST';

        fetch(url, {
            method: method,
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ value_ar, value_en })
        })
            .then(response => {
                if (!response.ok) {
                    return response.json().then(err => { throw err; });
                }
                return response.json();
            })
            .then(data => {
                closeAllModals();
                fetchAttributes();
            })
            .catch(error => {
                console.error('Error:', error);
                alert(error.message || 'حدث خطأ أثناء حفظ القيمة');
            });
    }

    // تبديل عرض الخيارات
    function toggleOptions(id) {
        const section = document.getElementById(`options-${id}`);
        const toggleIcon = section.previousElementSibling.querySelector('.toggle-icon');

        section.classList.toggle('show');

        if (section.classList.contains('show')) {
            toggleIcon.textContent = '🔼';
        } else {
            toggleIcon.textContent = '🔽';
        }
    }

    // تعديل سمة
    function editAttribute(id) {
        openAttributeModal(id);
    }

    // حذف سمة
    function deleteAttribute(id) {
        if (confirm('هل أنت متأكد من حذف هذه السمة؟ سيتم حذف جميع القيم المرتبطة بها.')) {
            fetch(`/api/attributes/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('فشل في حذف السمة');
                    }
                    fetchAttributes();
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert(error.message || 'حدث خطأ أثناء حذف السمة');
                });
        }
    }

    // إضافة قيمة جديدة
    function addNewOption(attributeId) {
        openOptionModal(attributeId);
    }

    // تعديل قيمة
    function editOption(attributeId, optionId) {
        openOptionModal(attributeId, optionId);
    }

    // حذف قيمة
    function deleteOption(attributeId, optionId) {
        if (confirm('هل أنت متأكد من حذف هذه القيمة؟')) {
            fetch(`/api/attributes/${attributeId}/options/${optionId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('فشل في حذف القيمة');
                    }
                    fetchAttributes();
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert(error.message || 'حدث خطأ أثناء حذف القيمة');
                });
        }
    }

    // بدء التطبيق
    document.addEventListener('DOMContentLoaded', init);
</script>
</body>
</html>

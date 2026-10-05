document.addEventListener('DOMContentLoaded', () => {
    const elements = {
        categoryFilter: document.getElementById("category-filter"),
        servicesSelect: document.getElementById("services"),
        quantityInput: document.getElementById("quantity"),
        saida: document.getElementById("saida"),
        totalPriceSpan: document.getElementById("total-price"),
        
        serviceInfo: document.getElementById("service-info"),
        infoType: document.getElementById("info-type"),
        infoMin: document.getElementById("info-min"),
        infoMax: document.getElementById("info-max"),
        infoRate: document.getElementById("info-rate"),

        divComentarios: document.getElementById("divComentarios"),
        commentInput: document.getElementById("comment-input"),
        addCommentBtn: document.getElementById("add-comment-btn"),
        previewComentarios: document.getElementById("previewComentarios"),
        inputComentarios: document.getElementById("comentarios"),
        saidaComentarios: document.getElementById("saidaComentarios"),
        targetQuantitySpan: document.getElementById("targetQuantity"),
        statusComentarios: document.getElementById("statusComentarios"),

        divAnswerNumber: document.getElementById("divAnswerNumber"),
        answerNumberInput: document.getElementById("answer_number"),

        divUsername: document.getElementById("divUsername"),
        usernameInput: document.getElementById("username"),

        divDripfeedContainer: document.getElementById("divDripfeedContainer"),
        dripfeedCheckbox: document.getElementById("dripfeed-checkbox"),
        dripfeedFields: document.getElementById("dripfeed-fields"),
        runsInput: document.getElementById("runs"),
        intervalInput: document.getElementById("interval"),

        totalQtyVal: document.getElementById("total-qty-val"),
        totalQtyDisplay: document.getElementById("total-quantity-display"),

        orderForm: document.querySelector("form"),
        description: document.getElementById("description")
    };

    let state = {
        comments: [],
        isCommentsService: false,
        isPollService: false,
        isCommentLikesService: false,
        supportsDripfeed: false,
        minQuantity: 0,
        maxQuantity: Infinity,
        currentRate: 0
    };

    const allServiceOptions = Array.from(elements.servicesSelect.querySelectorAll("option"));

    function init() {
        if (elements.categoryFilter) {
            elements.categoryFilter.addEventListener("change", handleCategoryChange);
        }
        if (elements.servicesSelect) {
            elements.servicesSelect.addEventListener("change", handleServiceChange);
        }
        if (elements.quantityInput) {
            elements.quantityInput.addEventListener("input", handleQuantityInput);
        }
        if (elements.addCommentBtn) {
            elements.addCommentBtn.addEventListener("click", addComment);
        }
        if (elements.commentInput) {
            elements.commentInput.addEventListener("keypress", handleCommentKeypress);
        }
        if (elements.previewComentarios) {
            elements.previewComentarios.addEventListener("click", handleRemoveComment);
        }
        if (elements.dripfeedCheckbox) {
            elements.dripfeedCheckbox.addEventListener("change", handleDripfeedToggle);
        }
        if (elements.runsInput) {
            elements.runsInput.addEventListener("input", calculateTotal);
        }
        if (elements.orderForm) {
            elements.orderForm.addEventListener("submit", handleFormSubmit);
        }
    }

    function handleCategoryChange() {
        const selectedCategory = elements.categoryFilter.value;
        elements.servicesSelect.innerHTML = '<option value="" selected disabled>Escolha um serviço</option>';

        allServiceOptions.forEach(option => {
            if (!option.value) return;
            const category = option.getAttribute("data-category");
            if (!selectedCategory || category === selectedCategory) {
                elements.servicesSelect.appendChild(option);
            }
        });

        resetState();
    }

    function handleServiceChange() {
        const option = elements.servicesSelect.options[elements.servicesSelect.selectedIndex];
        if (!option || !option.value) return;
        state.minQuantity = parseInt(option.getAttribute("data-min")) || 0;
        state.maxQuantity = parseInt(option.getAttribute("data-max")) || Infinity;
        state.currentRate = parseFloat(option.getAttribute("data-rate")) || 0;
        let serviceIndex = option.value;
        const description = option.getAttribute("data-description");
        if (description && description.trim() !== "") {
            elements.description.innerHTML = description;
        } else {
            switch(parseInt(serviceIndex)){
            case 195:
            elements.description.innerHTML = `<div class="aviso-servico">
  <p><strong>[ESTE SERVIÇO PODE APRESENTAR INSTABILIDADE]</strong></p>
  <p>🚨 <strong>AVISO:</strong> Devido às recentes atualizações do Instagram, serviços de seguidores com refil estão temporariamente indisponíveis. Recomendamos utilizar Seguidores Reais ou Orgânicos, que estão mais estáveis. Pedidos anteriores continuam com suporte normalmente.</p>

  <ul>
    <li>⏳ <strong>Início:</strong> 1-12 horas</li>
    <li>📊 <strong>Velocidade:</strong> Até 10.000 por dia</li>
    <li>🇧🇷 <strong>Público:</strong> Brasil</li>
    <li>🏆 <strong>Qualidade:</strong> Média Qualidade</li>
    <li>♻️ <strong>Reposição:</strong> Sem garantia</li>
    <li>🚫 <strong>Cancelamento:</strong> Não disponível após processamento</li>
  </ul>

  <p>🔗 <strong>Link aceito:</strong><br>
  ⚠️ <strong>ATENÇÃO:</strong> o link abaixo é APENAS UM EXEMPLO. Você precisa colocar o link DO SEU PRÓPRIO perfil, não copie esse link de exemplo.</p>
  
  <p>Exemplo: <code>instagram.com/neymarjr</code></p>

  <hr>

  <p>⛔ <strong>ATUALIZAÇÃO IMPORTANTE DO INSTAGRAM</strong> ⛔<br>
  Antes de pedir o serviço, desative o recurso "Sinalizar para revisão":</p>
  <ol>
    <li>Acesse Configurações e atividades</li>
    <li>Toque em Seguir e convidar amigos</li>
    <li>Desative Sinalizar para revisão</li>
  </ol>
  <p>Se o recurso estiver ativo, o pedido pode ser sinalizado pelo Instagram. Não há reembolso para perfis com a flag ativada.</p>

  <hr>

  <p>⚠️ <strong>Importante:</strong></p>
  <ul>
    <li>🔓 O perfil precisa estar aberto (modo público).</li>
    <li>❌ Só faça um novo pedido para o mesmo link após o anterior estar marcado como CONCLUÍDO.</li>
    <li>🔀 No campo Link, preencha com a URL correta conforme o exemplo acima (sem espaços extras).</li>
  </ul>

  <p>📌 <strong>Observação:</strong><br>
  Quando o serviço está ocupado, o tempo de início pode variar entre 3 minutos e 24 horas. Nunca faça um segundo pedido para o mesmo link antes do primeiro ser concluído.</p>

  <p>📊 Os dados acima são estimativas registradas na data de cadastro do serviço. Velocidade, qualidade e queda podem oscilar para mais ou para menos por fatores externos ao painel.</p>
</div>`;
break;
case 1066:
    elements.description.innerHTML = `<p>🚨 <strong>AVISO:</strong> Devido às recentes atualizações do Instagram, serviços de seguidores com refil estão instáveis em diversos Painéis. esse serviço além de queda baixa, é rápido. caso queira esse serviço mais caro com REFIL, use o ID1067.</p>

<ul>
  <li>⏳ <strong>Início:</strong> 15-60 Minutos</li>
  <li>📊 <strong>Velocidade:</strong> Até 5.000 por dia</li>
  <li>🇧🇷 <strong>Público:</strong> Brasileiros reais</li>
  <li>🏆 <strong>Qualidade:</strong> Premium ⭐</li>
  <li>♻️ <strong>Reposição:</strong> Sem reposição</li>
  <li>🚫 <strong>Cancelamento:</strong> Não disponível após processamento</li>
  <li>🔗 <strong>Link aceito:</strong> instagram.com/neymarjr; abra o perfil e copie usuário sem @ ou URL.</li>
</ul>

<p>⚠️ <strong>ATENÇÃO:</strong> o link abaixo é APENAS UM EXEMPLO. Você precisa colocar o link DO SEU PRÓPRIO perfil/conteúdo, não copie esse link de exemplo.<br>
Exemplo: instagram.com/neymarjr</p>

<p><strong>Como pegar o seu link correto:</strong><br>
Abra o perfil no aplicativo ou navegador, toque nos três pontinhos ou no botão de compartilhar e copie a URL do perfil ou preencha com o usuário sem o @.</p>

<p>⛔ <strong>ATUALIZAÇÃO IMPORTANTE DO INSTAGRAM</strong> ⛔<br>
Antes de pedir seguidores, desative o recurso "Sinalizar para revisão":</p>
<ol>
  <li>Acesse Configurações e atividades</li>
  <li>Toque em Seguir e convidar amigos</li>
  <li>Desative Sinalizar para revisão</li>
</ol>
<p>Se o recurso estiver ativo, os seguidores serão sinalizados pelo Instagram. Não há reembolso para perfis com a flag ativada.</p>

<p>⚠️ <strong>Importante:</strong></p>
<ul>
  <li>🔓 O perfil precisa estar aberto (modo público).</li>
  <li>❌ Só faça um novo pedido para o mesmo perfil após o anterior estar marcado como CONCLUÍDO.</li>
  <li>🔀 No campo Link, preencha conforme indicado acima -- não cole link de foto, post ou stories quando o formato pede usuário ou link de perfil.</li>
</ul>

<p>📌 <strong>Observação:</strong><br>
Quando o serviço está ocupado, o tempo de início pode variar entre 3 minutos e 24 horas. Nunca faça um segundo pedido para o mesmo link antes do primeiro ser concluído.</p>

<p>📊 Os dados acima são estimativas registradas na data de cadastro do serviço. Velocidade, qualidade e queda podem oscilar para mais ou para menos por fatores externos ao painel.</p>
`
            break;
            case 1067:
                elements.description.innerHTML = `<p>🚨 <strong>AVISO:</strong> Devido às recentes atualizações do Instagram, serviços de seguidores com refil estão instáveis em diversos Painéis. esse serviço além de queda baixa, é rápido. caso queira esse serviço mais barato sem REFIL, use o ID1066.</p>

<ul>
  <li>⏳ <strong>Início:</strong> 15-60 Minutos</li>
  <li>📊 <strong>Velocidade:</strong> Até 5.000 por dia</li>
  <li>🇧🇷 <strong>Público:</strong> Brasileiros reais</li>
  <li>🏆 <strong>Qualidade:</strong> Premium ⭐</li>
  <li>♻️ <strong>Reposição:</strong> 30 dias</li>
  <li>🚫 <strong>Cancelamento:</strong> Não disponível após processamento</li>
  <li>🔗 <strong>Link aceito:</strong> instagram.com/neymarjr; abra o perfil e copie usuário sem @ ou URL.</li>
</ul>

<p>⚠️ <strong>ATENÇÃO:</strong> o link abaixo é <strong>APENAS UM EXEMPLO</strong>. Você precisa colocar o link <strong>DO SEU PRÓPRIO</strong> perfil/conteúdo, não copie esse link de exemplo.<br>
Exemplo: instagram.com/neymarjr</p>

<p><strong>Como pegar o seu link correto:</strong><br>
Abra o seu perfil no Instagram, toque nos três pontinhos (ou no botão de compartilhar) e copie o nome de usuário (sem o @) ou a URL completa do seu perfil.</p>

<p>⛔ <strong>ATUALIZAÇÃO IMPORTANTE DO INSTAGRAM</strong> ⛔<br>
Antes de pedir seguidores, desative o recurso “Sinalizar para revisão”:</p>
<ol>
  <li>Acesse Configurações e atividades</li>
  <li>Toque em Seguir e convidar amigos</li>
  <li>Desative Sinalizar para revisão</li>
</ol>
<p>Se o recurso estiver ativo, os seguidores serão sinalizados pelo Instagram. Não há reembolso para perfis com a flag ativada.</p>

<p>⚠️ <strong>Importante:</strong></p>
<ul>
  <li>🔓 O perfil precisa estar aberto (modo público).</li>
  <li>❌ Só faça um novo pedido para o mesmo perfil após o anterior estar marcado como CONCLUÍDO.</li>
  <li>🔀 No campo Link, preencha conforme indicado acima — não cole link de foto, post ou stories quando o formato pede usuário ou link de perfil.</li>
</ul>

<p>📌 <strong>Observação:</strong><br>
Quando o serviço está ocupado, o tempo de início pode variar entre 3 minutos e 24 horas. Nunca faça um segundo pedido para o mesmo link antes do primeiro ser concluído.</p>

<p>📊 Os dados acima são estimativas registradas na data de cadastro do serviço. Velocidade, qualidade e queda podem oscilar para mais ou para menos por fatores externos ao painel.</p>
`
break;
	case 107:
        elements.description.innerHTML = `⏳ Início: 3-15 Minutos
📊 Velocidade: Conforme demanda
🌍 Público: Brasil
🏆 Qualidade: Média Qualidade
♻️ Reposição: Sem garantia
🚫 Cancelamento: Não disponível após processamento

🔗 Link aceito:
⚠️ ATENÇÃO: o link abaixo é APENAS UM EXEMPLO. Você precisa colocar o link DO SEU PRÓPRIO perfil, não copie esse link de exemplo.

Exemplo: instagram.com/neymarjr

⛔ ATUALIZAÇÃO IMPORTANTE DO INSTAGRAM ⛔
Antes de pedir o serviço, desative o recurso "Sinalizar para revisão":
1. Acesse Configurações e atividades
2. Toque em Seguir e convidar amigos
3. Desative Sinalizar para revisão
Se o recurso estiver ativo, o pedido pode ser sinalizado pelo Instagram. Não há reembolso para perfis com a flag ativada.

⚠️ Importante:
🔓 O perfil precisa estar aberto (modo público).
❌ Só faça um novo pedido para o mesmo link após o anterior estar marcado como CONCLUÍDO.
🔀 No campo Link, preencha com a URL correta conforme o exemplo acima (sem espaços extras).

📌 Observação:
Quando o serviço está ocupado, o tempo de início pode variar entre 3 minutos e 24 horas. Nunca faça um segundo pedido para o mesmo link antes do primeiro ser concluído.

📊 Os dados acima são estimativas registradas na data de cadastro do serviço. Velocidade, qualidade e queda podem oscilar para mais ou para menos por fatores externos ao painel.`
        break;
        case 120:
            elements.description.innerHTML = `⏳ Início: 3-15 Minutos
📊 Velocidade: Conforme demanda
🌍 Público: Brasil
🏆 Qualidade: Média Qualidade
♻️ Reposição: Reposição de 30 dias
🚫 Cancelamento: Não disponível após processamento

🔗 Link aceito:
⚠️ ATENÇÃO: o link abaixo é APENAS UM EXEMPLO. Você precisa colocar o link DO SEU PRÓPRIO perfil, não copie esse link de exemplo.

Exemplo: instagram.com/neymarjr

⛔ ATUALIZAÇÃO IMPORTANTE DO INSTAGRAM ⛔
Antes de pedir o serviço, desative o recurso "Sinalizar para revisão":
1. Acesse Configurações e atividades
2. Toque em Seguir e convidar amigos
3. Desative Sinalizar para revisão
Se o recurso estiver ativo, o pedido pode ser sinalizado pelo Instagram. Não há reembolso para perfis com a flag ativada.

⚠️ Importante:
🔓 O perfil precisa estar aberto (modo público).
❌ Só faça um novo pedido para o mesmo link após o anterior estar marcado como CONCLUÍDO.
🔀 No campo Link, preencha com a URL correta conforme o exemplo acima (sem espaços extras).

📌 Observação:
Quando o serviço está ocupado, o tempo de início pode variar entre 3 minutos e 24 horas. Nunca faça um segundo pedido para o mesmo link antes do primeiro ser concluído.

📊 Os dados acima são estimativas registradas na data de cadastro do serviço. Velocidade, qualidade e queda podem oscilar para mais ou para menos por fatores externos ao painel.`
            break;
            case 14:
                elements.description.innerHTML = `⏳ Início: 3-15 Minutos
📊 Velocidade: Conforme demanda
🌍 Público: Brasil
🏆 Qualidade: Média Qualidade
♻️ Reposição: Reposição de 30 dias
🚫 Cancelamento: Não disponível após processamento

🔗 Link aceito:
⚠️ ATENÇÃO: o link abaixo é APENAS UM EXEMPLO. Você precisa colocar o link DO SEU PRÓPRIO perfil, não copie esse link de exemplo.

Exemplo: instagram.com/neymarjr

⛔ ATUALIZAÇÃO IMPORTANTE DO INSTAGRAM ⛔
Antes de pedir o serviço, desative o recurso "Sinalizar para revisão":
1. Acesse Configurações e atividades
2. Toque em Seguir e convidar amigos
3. Desative Sinalizar para revisão
Se o recurso estiver ativo, o pedido pode ser sinalizado pelo Instagram. Não há reembolso para perfis com a flag ativada.

⚠️ Importante:
🔓 O perfil precisa estar aberto (modo público).
❌ Só faça um novo pedido para o mesmo link após o anterior estar marcado como CONCLUÍDO.
🔀 No campo Link, preencha com a URL correta conforme o exemplo acima (sem espaços extras).

📌 Observação:
Quando o serviço está ocupado, o tempo de início pode variar entre 3 minutos e 24 horas. Nunca faça um segundo pedido para o mesmo link antes do primeiro ser concluído.

📊 Os dados acima são estimativas registradas na data de cadastro do serviço. Velocidade, qualidade e queda podem oscilar para mais ou para menos por fatores externos ao painel.`
                break;
                case 15:
                    elements.description.innerHTML = `⏳ Início: 3-15 Minutos
📊 Velocidade: Conforme demanda
🌍 Público: Brasil
🏆 Qualidade: Média Qualidade
♻️ Reposição: Reposição de 30 dias
🚫 Cancelamento: Não disponível após processamento

🔗 Link aceito:
⚠️ ATENÇÃO: o link abaixo é APENAS UM EXEMPLO. Você precisa colocar o link DO SEU PRÓPRIO perfil, não copie esse link de exemplo.

Exemplo: instagram.com/neymarjr

⛔ ATUALIZAÇÃO IMPORTANTE DO INSTAGRAM ⛔
Antes de pedir o serviço, desative o recurso "Sinalizar para revisão":
1. Acesse Configurações e atividades
2. Toque em Seguir e convidar amigos
3. Desative Sinalizar para revisão
Se o recurso estiver ativo, o pedido pode ser sinalizado pelo Instagram. Não há reembolso para perfis com a flag ativada.

⚠️ Importante:
🔓 O perfil precisa estar aberto (modo público).
❌ Só faça um novo pedido para o mesmo link após o anterior estar marcado como CONCLUÍDO.
🔀 No campo Link, preencha com a URL correta conforme o exemplo acima (sem espaços extras).

📌 Observação:
Quando o serviço está ocupado, o tempo de início pode variar entre 3 minutos e 24 horas. Nunca faça um segundo pedido para o mesmo link antes do primeiro ser concluído.

📊 Os dados acima são estimativas registradas na data de cadastro do serviço. Velocidade, qualidade e queda podem oscilar para mais ou para menos por fatores externos ao painel.`
                break;
                case 458:
                    elements.description.innerHTML = `⏳ Início: 3-15 Minutos
📊 Velocidade: Conforme demanda
🌍 Público: Brasil reais
🏆 Qualidade: Alta Qualidade
♻️ Reposição: Sem garantia
🚫 Cancelamento: Não disponível após processamento

🔗 Link aceito:
⚠️ ATENÇÃO: o link abaixo é APENAS UM EXEMPLO. Você precisa colocar o link DO SEU PRÓPRIO perfil, não copie esse link de exemplo.

Exemplo: instagram.com/neymarjr

⛔ ATUALIZAÇÃO IMPORTANTE DO INSTAGRAM ⛔
Antes de pedir o serviço, desative o recurso "Sinalizar para revisão":
1. Acesse Configurações e atividades
2. Toque em Seguir e convidar amigos
3. Desative Sinalizar para revisão
Se o recurso estiver ativo, o pedido pode ser sinalizado pelo Instagram. Não há reembolso para perfis com a flag ativada.

⚠️ Importante:
🔓 O perfil precisa estar aberto (modo público).
❌ Só faça um novo pedido para o mesmo link após o anterior estar marcado como CONCLUÍDO.
🔀 No campo Link, preencha com a URL correta conforme o exemplo acima (sem espaços extras).

📌 Observação:
Quando o serviço está ocupado, o tempo de início pode variar entre 3 minutos e 24 horas. Nunca faça um segundo pedido para o mesmo link antes do primeiro ser concluído.

📊 Os dados acima são estimativas registradas na data de cadastro do serviço. Velocidade, qualidade e queda podem oscilar para mais ou para menos por fatores externos ao painel.`
			break;
            case 982:
                elements.description.innerHTML = `---------[ SERVIÇO LENTO / FEITO MANUALMENTE ]---------

⏳ Início: 3-15 Minutos
📊 Velocidade: Conforme demanda
🌍 Público: Brasileiros
🏆 Qualidade: Alta Qualidade
♻️ Reposição: 30 dias
🚫 Cancelamento: Não disponível após processamento
🔗 Link aceito:
⚠️ ATENÇÃO: o link abaixo é APENAS UM EXEMPLO. Você precisa colocar o link DO SEU PRÓPRIO perfil, não copie esse link de exemplo.

Exemplo: instagram.com/neymarjr

Como pegar o seu link correto:
Abra seu perfil no Instagram, copie o nome de usuário que aparece no topo (sem @) ou copie a URL completa da barra de endereço quando estiver na sua página de perfil.

⛔ ATUALIZAÇÃO IMPORTANTE DO INSTAGRAM ⛔
Antes de pedir seguidores, desative o recurso "Sinalizar para revisão":
1. Acesse Configurações e atividades
2. Toque em Seguir e convidar amigos
3. Desative Sinalizar para revisão
Se o recurso estiver ativo, os seguidores serão sinalizados pelo Instagram. Não há reembolso para perfis com a flag ativada.

⚠️ Importante:
🔓 O perfil precisa estar aberto (modo público).
❌ Só faça um novo pedido para o mesmo perfil após o anterior estar marcado como CONCLUÍDO.
🔀 No campo Link, preencha com o usuário do seu perfil (não cole link de foto, post ou stories).

📌 Observação:
Quando o serviço está ocupado, o tempo de início pode variar entre 3 minutos e 24 horas. Nunca faça um segundo pedido para o mesmo link antes do primeiro ser concluído.

📊 Os dados acima são estimativas registradas na data de cadastro do serviço. Velocidade, qualidade e queda podem oscilar para mais ou para menos por fatores externos ao painel.`
            break;
            default:
                elements.description.innerHTML = '<p class="text-gray-500 italic">Sem descrição disponível para este serviço.</p>';
        }
    }
        
        
        const type = option.getAttribute("data-type") || "Default";
        const dripfeedAttr = option.getAttribute("data-dripfeed") === "true";

        state.isCommentsService = type === "Custom Comments";
        state.isPollService = type === "Poll";
        state.isCommentLikesService = type === "Comment Likes";
        state.supportsDripfeed = dripfeedAttr;

        if (elements.serviceInfo) {
            elements.serviceInfo.classList.remove("hidden");
            elements.infoType.innerText = type;
            elements.infoMin.innerText = state.minQuantity;
            elements.infoMax.innerText = state.maxQuantity;
            elements.infoRate.innerText = state.currentRate.toFixed(2);
        }

        elements.quantityInput.setAttribute("min", state.minQuantity);
        elements.quantityInput.setAttribute("max", state.maxQuantity);
        elements.quantityInput.value = state.minQuantity;
        
        updateQuantityDisplay();
        
        if (state.isCommentsService) {
            elements.divComentarios.classList.remove("hidden");
            state.comments = [];
            updateCommentsUI();
        } else {
            elements.divComentarios.classList.add("hidden");
        }

        if (elements.divAnswerNumber) {
            if (state.isPollService) {
                elements.divAnswerNumber.classList.remove("hidden");
                elements.answerNumberInput.setAttribute("required", "required");
            } else {
                elements.divAnswerNumber.classList.add("hidden");
                elements.answerNumberInput.removeAttribute("required");
            }
        }

        if (elements.divUsername) {
            if (state.isCommentLikesService) {
                elements.divUsername.classList.remove("hidden");
                elements.usernameInput.setAttribute("required", "required");
            } else {
                elements.divUsername.classList.add("hidden");
                elements.usernameInput.removeAttribute("required");
            }
        }

        if (elements.divDripfeedContainer) {
            if (state.supportsDripfeed) {
                elements.divDripfeedContainer.classList.remove("hidden");
            } else {
                elements.divDripfeedContainer.classList.add("hidden");
                elements.dripfeedCheckbox.checked = false;
                elements.dripfeedFields.classList.add("hidden");
            }
        }

        calculateTotal();
    }

    function handleQuantityInput() {
        let qty = parseInt(elements.quantityInput.value) || 0;

        if (elements.quantityInput.value !== "") {
            if (qty < state.minQuantity) {
                qty = state.minQuantity;
                elements.quantityInput.value = state.minQuantity;
            } else if (qty > state.maxQuantity) {
                qty = state.maxQuantity;
                elements.quantityInput.value = state.maxQuantity;
            }
        }

        updateQuantityDisplay();
        calculateTotal();
        if (state.isCommentsService) {
            updateCommentsUI();
        }
    }

    function handleDripfeedToggle() {
        if (elements.dripfeedCheckbox.checked) {
            elements.dripfeedFields.classList.remove("hidden");
            elements.totalQtyDisplay.classList.remove("hidden");
        } else {
            elements.dripfeedFields.classList.add("hidden");
            elements.totalQtyDisplay.classList.add("hidden");
        }
        calculateTotal();
    }

    function handleCommentKeypress(e) {
        if (e.key === "Enter") {
            e.preventDefault();
            addComment();
        }
    }

    function handleRemoveComment(e) {
        const removeBtn = e.target.closest(".remove-comment");
        if (removeBtn) {
            const index = parseInt(removeBtn.getAttribute("data-index"));
            state.comments.splice(index, 1);
            updateCommentsUI();
        }
    }

    function handleFormSubmit(e) {
        if (state.isCommentsService) {
            const targetQty = parseInt(elements.quantityInput.value) || 0;
            if (state.comments.length !== targetQty) {
                e.preventDefault();
                alert(`Erro: Você precisa adicionar exatamente ${targetQty} comentários. Atualmente você tem ${state.comments.length}.`);
                return false;
            }
        }
    }

    function addComment() {
        const comment = elements.commentInput.value.trim();
        const targetQty = parseInt(elements.quantityInput.value) || 0;

        if (!comment) return;

        if (state.comments.length >= targetQty) {
            alert(`Limite atingido! A quantidade é ${targetQty}, então você só pode adicionar ${targetQty} comentários.`);
            return;
        }

        state.comments.push(comment);
        elements.commentInput.value = "";
        updateCommentsUI();
        elements.commentInput.focus();
    }

    function updateCommentsUI() {
        const targetQty = parseInt(elements.quantityInput.value) || 0;
        elements.saidaComentarios.innerText = state.comments.length;
        elements.targetQuantitySpan.innerText = targetQty;
        
        elements.inputComentarios.value = state.comments.join("\n");

        renderPreview();
        updateStatusMessage(targetQty);

        elements.addCommentBtn.disabled = (state.comments.length >= targetQty && targetQty > 0);
        elements.addCommentBtn.style.opacity = elements.addCommentBtn.disabled ? "0.5" : "1";
    }

    function renderPreview() {
        if (state.comments.length === 0) {
            elements.previewComentarios.innerHTML = '<p class="text-gray-400 text-xs text-center py-2">Nenhum comentário adicionado</p>';
            return;
        }

        elements.previewComentarios.innerHTML = state.comments.map((comment, index) => `
            <div class="flex justify-between items-center bg-gray-50 p-2 rounded border border-gray-200 text-sm mb-1">
                <span class="truncate mr-2 flex-1">
                    <b class="text-blue-600">${index + 1}.</b> ${comment}
                </span>
                <button type="button" class="remove-comment text-red-500 hover:text-red-700 text-xs px-2" data-index="${index}" title="Remover">✕</button>
            </div>
        `).join("");
    }

    function updateStatusMessage(targetQty) {
        if (targetQty === 0) {
            elements.statusComentarios.innerText = "Defina a quantidade primeiro";
            elements.statusComentarios.className = "text-gray-500";
        } else if (state.comments.length === targetQty) {
            elements.statusComentarios.innerText = "✓ Tudo pronto!";
            elements.statusComentarios.className = "text-green-600 font-bold";
        } else if (state.comments.length > targetQty) {
            elements.statusComentarios.className = "text-red-600 font-bold";
            elements.statusComentarios.innerText = "⚠ Excesso!";
        } else {
            elements.statusComentarios.innerText = `Faltam ${targetQty - state.comments.length}`;
            elements.statusComentarios.className = "text-orange-500";
        }
    }

    function updateQuantityDisplay() {
        const qty = elements.quantityInput.value || 0;
        elements.saida.innerText = qty;
        if (elements.targetQuantitySpan) {
            elements.targetQuantitySpan.innerText = qty;
        }
    }

    function calculateTotal() {
        const qty = parseInt(elements.quantityInput.value) || 0;
        let runs = 1;
        if (elements.dripfeedCheckbox && elements.dripfeedCheckbox.checked) {
            runs = parseInt(elements.runsInput.value) || 1;
        }

        const totalQty = qty * runs;
        if (elements.totalQtyVal) {
            elements.totalQtyVal.innerText = totalQty;
        }

        const total = (totalQty * state.currentRate) / 1000;
        elements.totalPriceSpan.innerText = total.toLocaleString('pt-BR', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    }

    function resetState() {
        elements.quantityInput.value = "";
        elements.saida.innerText = "0";
        elements.totalPriceSpan.innerText = "0.00";
        if (elements.serviceInfo) elements.serviceInfo.classList.add("hidden");
        if (elements.divComentarios) elements.divComentarios.classList.add("hidden");
        if (elements.divAnswerNumber) elements.divAnswerNumber.classList.add("hidden");
        if (elements.divUsername) elements.divUsername.classList.add("hidden");
        if (elements.divDripfeedContainer) elements.divDripfeedContainer.classList.add("hidden");
        state.comments = [];
        state.minQuantity = 0;
        state.maxQuantity = Infinity;
        state.currentRate = 0;
        state.isCommentsService = false;
        state.isPollService = false;
        state.isCommentLikesService = false;
        state.supportsDripfeed = false;
    }

    init();
});

@extends('layouts.page')

@section('page-level-styles')
    @parent
    <style>
        .caption-desc {
            line-height: normal;
        }

        .text-justify {
            text-align: justify;
        }
    </style>
@endsection

@section('page-body')
    <div class="row">
        <div class="col-lg-12">
            <div class="portlet light">
                <div class="portlet-title">
                    <div class="caption">
                        <div class="caption-subject bold" style="color: #31849b; font-size: 18pt;">
                            V-CitationGate
                        </div>
                        <div class="caption-desc bold" style="font-size: 11pt; color: #28ACE2">
                            Vietnam Citation Gateway
                            <br>Tư liệu nghiên cứu Việt Nam
                        </div>
                    </div>
                </div>
                <div class="portlet-body text-justify">
                    <p>
                        <i>Vietnam Citation Gateway (V-CitationGate)</i> là cơ sở dữ liệu thư mục <i>(Bibliographic database)</i>,
                        đồng thời là trung tâm phân tích, đánh giá khoa học, công nghệ và đổi mới sáng tạo
                        <i>(Scientometrics)</i> của Việt Nam. <i>V-CitationGate</i> bao gồm thông tin (tóm tắt hoặc/và toàn văn) của
                        các ấn phẩm khoa học đương đại (bài báo tạp chí, sách), các phát minh, sáng chế, và đặc biệt,
                        bao gồm cả các tài liệu quý, cổ được sưu tập, số hóa, kết nối và tích hợp từ các nguồn lưu trữ ở
                        Việt Nam và nước ngoài.
                    </p>
                    <p>
                        Với một cơ sở dữ liệu thư mục phong phú và đầy đủ, <i>V-CitationGate</i> sẽ phát triển thành “thánh
                        địa” phục vụ cho việc học tập và nghiên cứu Việt Nam. Phần mềm của hệ thống còn cho phép thực
                        hiện các tìm kiếm, phân tích, thống kê và trích xuất thông tin khoa học ở các cấp độ khác nhau,
                        từ tác giả đến đơn vị; chủ đề đến lĩnh vực, nhóm lĩnh vực; thời gian xuất bản đến mức độ hợp tác
                        nghiên cứu… phục vụ công tác quản lý, hoạch định chính sách phát triển.
                    </p>
                    <p>
                        Bên cạnh mục đích học thuật, <i>V-CitationGate</i> cung cấp các số liệu thống kê để xây dựng và công bố
                        báo cáo thường niên, xếp hạng nghiên cứu của các cơ sở nghiên cứu và giáo dục. Đây là nguồn
                        thông tin minh bạch về năng suất và chất lượng nghiên cứu khoa học của các cơ sở giáo dục đại
                        học, sẽ được Viện Đảm bảo Chất lượng Giáo dục và Trung tâm Kiểm định Chất lượng Giáo dục Quốc
                        gia của Đại học Quốc gia Hà Nội tham khảo và sử dụng khi tiến hành đánh giá kiểm định các trường
                        đại học.
                    </p>
                    <p>
                        Hệ thống cơ sở dữ liệu này cũng là thông tin hỗ trợ cho các Cơ quan tài trợ nghiên cứu xét chọn
                        và đánh giá về mức độ trùng lặp của đề tài nghiên cứu và năng lực, thành tích nghiên cứu của ứng
                        viên.
                    </p>
                    <p>
                        Ở một khía cạnh khác, thông qua chỉ số trích dẫn, chất lượng các Tạp chí khoa học của Việt nam
                        cũng sẽ được so sánh, đánh giá công khai và bình đẳng.
                    </p>
                    <p>
                        <i>V-CitationGate</i> rất mong nhận được sự chia sẻ và hợp tác của các nhà khoa học, các nhà xuất bản,
                        cơ sở nghiên cứu và đào tạo và các tổ chức quản lý, tài trợ khoa học công nghệ.
                    </p>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="portlet light">
                <div class="portlet-title">
                    <div class="caption">
                        <div class="caption-subject bold">
                            Lịch sử phát triển
                        </div>
                    </div>
                </div>
                <div class="portlet-body text-justify">
                    <p>
                        Quản lý năng suất và chất lượng nghiên cứu khoa học truyền thống thường được thực hiện chủ yếu
                        bằng phương pháp chuyên gia, dựa vào thông tin do các nhà khoa học cung cấp và ý kiến phản biện,
                        đánh giá của các chuyên gia. Phương pháp này đảm bảo được tự do học thuật, nhưng bị chi phối
                        nhiều bởi các yếu tố chủ quan, tính minh bạch và khả năng thống kê rất hạn chế. Để khắc phục
                        các bất cập đó, ngay từ năm 1955, các nhà khoa học trên thế giới đã xây dựng phương pháp đánh
                        giá định lượng năng suất và chất lượng nghiên cứu khoa học bằng phân tích trắc lượng chỉ số
                        trích dẫn [1]. Phương pháp này rất khách quan và hữu hiệu; hỗ trợ minh chứng cho phương pháp
                        chuyên gia.
                    </p>
                    <p>
                        Phương pháp đo lường và phân tích khoa học hiện đại được phát triển dựa trên các đề xuất trong
                        nghiên cứu của Derek J. de Solla Price [2] vào năm 1978. Sau đó, hệ thống Science Citation Index
                        (SCI) and và Viện Institute for Scientific Information (ISI) đã được thành lập ở Hoa Kỳ. Bước
                        sang thiên niên kỷ mới, việc đánh giá năng suất và chất lượng nghiên cứu của các nhà khoa học và
                        các cơ sở nghiên cứu, đào tạo càng có sự quan tâm lớn. Dựa vào các phân tích cơ sở dữ liệu thư
                        mục khoa học, Bảng xếp hạng các trường đại học (ARWR) [3] lần đầu tiên được Trường đại học Giao
                        Thông Thượng Hải công bố vào năm 2004. Cùng với đó, các bảng xếp hạng quốc tế khác như the Times
                        Higher Education World University Rankings (THE-ranking) [4] and Quacquarelli Symonds Rankings
                        (QS-ranking) [5] đã trở thành các chỉ số quen thuộc đánh giá thương hiệu của các trường đại học.
                    </p>
                    <p>
                        Hệ thống cơ sở dữ liệu thư mục khoa học đã và đang được xây dựng, phát triển mạnh mẽ ở nhiều
                        quốc gia với quy mô đa dạng, không chỉ phục vụ nhu cầu học tập, nghiên cứu và quản lý trong phạm
                        vi một quốc gia mà đã vươn ra toàn cầu như hệ thống cơ sở dữ liệu của ISI, Scopus, PubMed,
                        Google Scholar và cả ASEAN Citation Index - ACI. Ở Việt Nam, nhờ có các hệ thống cơ sở dữ liệu
                        thư mục đó, tình hình công bố các ấn phẩm khoa học trong hệ thống tạp chí quốc tế có thể được
                        thống kê, phân tích đầy đủ. Trong khi đó, số bài báo công bố trong hệ thống tạp chí khoa học
                        trong nước - một tài nguyên nội sinh của quốc gia - đang bị bỏ ngỏ. Tài nguyên này chưa được hệ
                        thống hóa, thống kê và phân tích một cách toàn diện. Do đó, việc quản lý, đánh giá chất lượng
                        công bố khoa học và chất lượng tạp chí khoa học trong nước trở nên rất hạn chế.
                    </p>
                    <p>
                        Năm 2016, Đại học Quốc gia Hà Nội đã có sáng kiến và khởi động cơ sở dữ liệu thư mục Chỉ số
                        trích dẫn Việt Nam (VCI) [6] và cơ sở dữ liệu thư mục Nghiên cứu Việt Nam [7]. Hai hệ thống này
                        được tích hợp chung thành <i>V-CitationGate</i>. Sau một năm hoạt động thử nghiệm và hoàn thiện, hiện
                        nay (4/2017) <i>V-CitationGate</i> đã kết nối thành công các ấn phẩm khoa học xuất bản trên 50 Tạp chí
                        khoa học của Việt Nam có thông tin trích dẫn trong nước và trên thế giới. Đây là nguồn cơ sở dữ
                        liệu thư mục đầu tiên để bắt đầu vận hành Đề án xây dựng Trung tâm tư liệu nghiên cứu Việt Nam ở
                        Đại học Quốc gia Hà Nội.
                    </p>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="portlet light">
                <div class="portlet-title">
                    <div class="caption">
                        <div class="caption-subject bold">
                            Nguồn cơ sở dữ liệu
                        </div>
                    </div>
                </div>
                <div class="portlet-body text-justify">
                    <p>
                        <i>V-CitationGate</i> kết nối và tích hợp thông tin từ các nguồn sau đây:
                    </p>
                    <p>
                        - Các Tạp chí của Việt Nam xuất bản online, có trang web gốc chuẩn mực, được index ít nhất vào
                        nguồn Google Scholar. Đây là kỹ thuật cơ bản, thông dụng và tin cậy để đánh giá chỉ số trích dẫn
                        của các bài báo, cá nhân và đơn vị. Ngoài việc cung cấp thông tin khoa học, <i>V-CitationGate</i> cũng
                        có khả năng đánh giá chất lượng các tạp chí khoa học.
                    </p>
                    <p>
                        - Các bài báo của các tác giả Việt Nam và các bài báo của các tác giả nước ngoài nghiên cứu về
                        Việt nam công bố trên hệ thống tạp chí khoa học thuộc ISI và Scopus.
                    </p>
                    <p>
                        - Thông tin về sáng chế, phát minh đã được đăng ký tại Cục sở hữu trí tuệ Việt Nam.
                    </p>
                    <p>
                        - Sách chuyên khảo xuất bản ở Việt Nam.
                    </p>
                    <p>
                        - Các tài liệu số hóa về các bài viết, tư liệu quý, cổ có nguồn từ các thư viện Việt Nam và nước ngoài.
                    </p>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="portlet light">
                <div class="portlet-title">
                    <div class="caption">
                        <div class="caption-subject bold">
                            Hỗ trợ phát triển nguồn cơ sở dữ liệu thư mục của các Tạp chí khoa học
                        </div>
                    </div>
                </div>
                <div class="portlet-body text-justify">
                    <p>
                        <i>V-CitationGate</i> có thể tư vấn hoặc/và hỗ trợ công nghệ để các Tạp chí trong nước xây dựng các
                        trang web chuẩn hoặc/và hosting, đảm bảo khả năng index vào nguồn Google Scholar và phát triển
                        chỉ số trích dẫn. Từ có, có thể được kết nối vào <i>V-CitationGate</i> hoặc/và các hệ thống cơ sở dữ
                        liệu khoa học quốc gia và quốc tế khác.
                    </p>
                    <p>
                        <i>V-CitationGate</i> còn có thể hỗ trợ các Tạp chí trong nước thiết lập định danh số DOI (Digital
                        Object Identifier) cho các ấn phẩm khoa học gốc đăng trên tạp chí thông qua hệ thống Crossref do
                        Đại học Quốc gia Hà Nội đăng ký và đại diện. DOI tạo điều kiện cho việc gửi, truy xuất dữ liệu
                        và nhận dạng số để kích hoạt, thúc đẩy liên kết bền vững và khả năng tìm kiếm các ấn phẩm khoa
                        học gốc trên Internet.
                    </p>
                    <p>
                        Thông tin liên hệ: email: {{Html::mailto('vcgate@vnu.edu.vn')}}
                    </p>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="portlet light">
                <div class="portlet-title">
                    <div class="caption">
                        <div class="caption-subject bold">
                            Tài liệu tham khảo
                        </div>
                    </div>
                </div>
                <div class="portlet-body text-justify">
                    <p>
                        1. E. Garfield, Citation indexes for science: a new dimension in documentation through
                        association of ideas, Science, 122 (1955) 108.
                    </p>
                    <p>
                        2. D. De Solla Price, Editorial statement, Scientometrics, 1(1) (1978) 2.
                    </p>
                    <p>
                        3. Bảng xếp hạng các trường đại học (ARWR), {{Html::link('http://www.shanghairanking.com/')}}
                    </p>
                    <p>
                        4. Bảng xếp hạng quốc tế Times Higher Education World University Rankings (THE-ranking), {{Html::link('https://www.timeshighereducation.com/world-university-rankings')}}.
                    </p>
                    <p>
                        5. Bảng xếp hạng quốc tế Quacquarelli Symonds Rankings (QS-ranking), {{Html::link('https://www.topuniversities.com/university-rankings')}}
                    </p>
                    <p>
                        6. Lần đầu tiên Việt Nam xây dựng hệ thống chỉ số trích dẫn khoa học, {{Html::link('http://dantri.com.vn/giao-duc-khuyen-hoc/lan-dau-tien-viet-nam-xay-dung-he-thong-chi-so-trich-dan-khoa-hoc-20160621144648035.htm')}}
                    </p>
                    <p>
                        7. Hình thành các nhóm nghiên cứu phối hợp quốc tế về Việt Nam, {{Html::link('http://dantri.com.vn/giao-duc-khuyen-hoc/hinh-thanh-cac-nhom-nghien-cuu-phoi-hop-quoc-te-ve-viet-nam-20161216210331994.htm')}}
                    </p>
                </div>
            </div>
        </div>
    </div>
@endsection
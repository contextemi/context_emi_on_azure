# CONTEXT CLOUD 



## IMAGE & DEMO LINK

![image](https://user-images.githubusercontent.com/26568793/56471350-540f5200-648c-11e9-8e62-04bb63e16c17.png)

   **LINK →**  https://www.youtube.com/watch?time_continue=2&v=paoO_Kr-f88

## 

## INTRODUCTION

1. **Summary**

   To make time stamp of the funny part and extract keyword of a given video. No need
   to skim all the way through it and manually work on time stamp. The goal of
   this service is to do such task automatically by AI. This service does so by
   using voice analysis and keyword extraction through the voice and scripts of
   the speaker in the registered video.

   해당 프로젝트는 video indexer에 등록된 영상을 이용하여 해당 동영상의 키워드를 추출 및 키워드에 해당하는 시간으로 이동 및 웃긴 부분을 추출하여 웃긴 부분으로 이동할 수 있는 서비스를 제공하는 웹서비스입니다.이는 기존 동영상 스트리밍 서비스에서 손수 타임스탬프를 제작해야했던 번거로움을 줄여줍니다. 

   

2. **Background : Problem definition and solution**

   Because of the nature of video media, we were able to browse the contents only through the slider. Until now, there was no other way. And we were able to see the part of where we want to see again only through the manual labored timestamp information.
    To solve this problem, we would like to develop a service that creates **word cloud** through a conversation of a speaker(speaker’s conversation) without need for the person to directly edit the video and moves to a desired instant by using keywords of a **word cloud**. So video viewers can select keywords created with **word cloud** and move to the part of interest.

   Also, using the **voice** **analysis**, the viewers can view thumbnails of funny parts of the registered video at a glance and move to the funny time zone.

   

   비디오라는 매체의 특성 상 우리는 영상을 볼 때 영상의 슬라이더를 이용하여 영상을 탐색합니다. 현재까지도 별다른 방식은 없습니다. 이 문제를 해결하기 위해서 키워드와 웃음을 추출하여 원하는 부분을 골라볼 수 있도록 서비스를 제공합니다.  효과적으로 서비스를 제공하기 위해서 워드 클라우드를 이용하고, 음성 분석을 이용합니다.

   

3. **Feature** 

   It is easier to understand the contents of the entire video than the existing streaming platform. Keywords can be used to show ads that are highly relevant to the content at a particular point in time of the video, which can increase the effectiveness of the ad.

   현존하는 스트리밍 플랫폼에 비해 보다 쉽게 영상 컨텐츠의 내용을 파악할 수 있습니다. 또한 키워드를 이용하여 광고주는 특정 부분에 키워드와 관련된 광고를 실어 보다 보율적이고 효과적인 광고이익을 얻어올 수 있습니다.

## Development Background

1. **Recently, there have been many studies that analyze the emotions of the speaker through recent videos and voices**

​        Emotional state can be observed/ measured in many different ways [1]

​        a. Facial expressions

​        b. Speech

​        c. Physiological signals



2. **Speech Recognition**

3. **NLP** **+ Audio transcription Service** 

4. **Automatic tagging Service**

   Automatic tagging service using Convolutional Neural Network [2] 

   In our service, we will use emotion analysis by facial expression service and speech offered by Microsoft Azure Cognitive. We will also use Audio transcription Service  by Azure Cognitive

​      [1]https://www.youtube.com/watch?v=KT6f8XSJwFE

​      [2]https://arxiv.org/pdf/1606.00298.pdf



## Service Architecture 

![image](https://user-images.githubusercontent.com/26568793/56471438-735aaf00-648d-11e9-8c5b-404a4ea002ae.png)

![1555857080028](C:\Users\Domirae\AppData\Roaming\Typora\typora-user-images\1555857080028.png)



## REFERENCE and USE

1. laughter detection : https://github.com/jrgillick/laughter-detection
2.  Azure Official Github:  <https://github.com/MicrosoftDocs/azure-docs/blob/651e591f985e6af327cbaad4582e2c1a1240e36a/articles/media-services/video-indexer/video-indexer-output-json-v2.md>

